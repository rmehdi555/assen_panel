<?php

namespace App\Http\Livewire\BuyInvoiceManagement;

use App\Helpers\Calculator;
use App\Models\Account;
use App\Models\Base;
use App\Models\CancelReason;
use App\Models\Exchanges;
use App\Models\InvoiceDescription;
use App\Models\InvoiceItem;
use App\Models\Logistic;
use App\Models\Source;
use App\Models\SourcePaylog;
use App\Models\TransitionHistory;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use function Filament\Support\format_number;

class BuyItemRow extends Component implements HasForms
{
    use InteractsWithForms;

    public $invoiceItem;
    public $invoiceId;

    public $preOrderComment;

    public $issensitive;
    public $account_id;
    public $price;
    public $account_discount;
    public $estimateddatetobase;
    public $base_id;
    public $source_id;
    public $count;
    public $orderId;
    public $suspended_reason;
    public $description;
    public $exchangeValue;
    public $exchangeValueLable;
    public $exchangeValueLogistic;
    public $exchangeRealValue;

    public function mount($invoiceItem, $invoiceId): void
    {
        $this->invoiceItem = $invoiceItem;
        $this->invoiceId = $invoiceId;
        $this->preOrderComment = $invoiceItem->preordercomment;

    }

    protected function getFormSchema(): array
    {
        $this->price = $this->invoiceItem->cost;
        $this->account_discount = 0;
        $this->exchangeValue = round(Exchanges::where('id', $this->invoiceItem->exchange_id)->value('value'));
        $this->exchangeRealValue = round(Exchanges::where('id', $this->invoiceItem->exchange_id)->value('value'));
        $this->exchangeName = Exchanges::where('id', $this->invoiceItem->exchange_id)->value('name');
        $this->exchangeValueLogistic = Logistic::where('invoice_item_id', $this->invoiceItem->id)->first()->exchangevalue ?? null;
        if ($this->exchangeValueLogistic == null) {
            $this->exchangeValueLable = 'قیمت ارز';
            $filedAdd = TextInput::make('price')->label(' قیمت (' . $this->exchangeName . ' )')->numeric()->required();
        } else {
            $this->exchangeValue = $this->exchangeValueLogistic;
            $this->exchangeValueLable = 'قیمت ارز ( براساس خرید قبلی این سفارش)';
            $this->price = Logistic::where('invoice_item_id', $this->invoiceItem->id)->first()->price;
            $filedAdd = TextInput::make('price')->label(' قیمت (' . $this->exchangeName . ' )' . ' ( براساس خرید قبلی این سفارش)')->numeric()->required()->disabled();
        }

        return [
            Grid::make(2)->schema([
                Select::make('issensitive')->label('صنعتی')->required()->options([
                    0 => 'خیر',
                    1 => 'بله'
                ]),

                Select::make('account_id')->label('اکانت')->required()->options(
                    Account::all()->pluck('account_name', 'id')
                ),

                $filedAdd,
                TextInput::make('account_discount')->label('تخفیف اکانت (' . $this->exchangeName . ' )')->numeric()->required()->minValue(0),
                DatePicker::make('estimateddatetobase')->label('تاریخ تخمینی بیس')->required()->displayFormat('Y-m-d'),
                Select::make('base_id')->label('بیس')->required()->options(
                    Base::all()->pluck('title', 'id')
                ),

                Select::make('source_id')->label('منبع')->required()->options(
                    Source::where('exchange_id', $this->invoiceItem->exchange_id)->get()->pluck('name', 'id')
                ),
                TextInput::make('count')->label('تعداد')->numeric()->required(),
                TextInput::make('exchangeValue')->label($this->exchangeValueLable)->required()->numeric()->disabled(),
                TextInput::make('exchangeRealValue')->label('قیمت واقعی ارز خریداری شده')->required()->numeric(),
                TextInput::make('orderId')->label('شماره سفارش')->required(),
            ]),
            Select::make('suspended_reason')->label('دلیل کنسلی')->placeholder('در صورت کنسل کردن انتخاب کنید')->options(
                CancelReason::all()->pluck('title', 'id')
            ),
            Textarea::make('description')->label('توضیحات کارشناس خرید')
        ];
    }

    public function addPreOrderComment(): void
    {
        InvoiceItem::where('id', $this->invoiceItem->id)->update([
            'preordercomment' => $this->preOrderComment
        ]);

        Notification::make()->title('توضیحات پیش از خرید با موفقیت ذخیره شد.')->success()->send();
    }

    public function submit()
    {
        $this->validate();

        if (Carbon::parse($this->estimateddatetobase)->lt(now()->subDay())) {
            Notification::make()->title('تاریخ تخمینی بیس کوچکتر از تاریخ جاری می باشد.')->danger()->send();
            return;
        } elseif ($this->invoiceItem->logistics->count() + (int)$this->count > $this->invoiceItem->count) {
            Notification::make()->title('تعداد خرید از تعداد درخواستی بیشتر است.')->danger()->send();
            return;
        }
        $cal = Calculator::singleProduct(
            price: $this->price,
            massUnit: 'گرم',
            exchangeType: $this->invoiceItem->exchange_id,
            region: $this->invoiceItem->region_id,
            weight: $this->invoiceItem->firstweight,
            exchangeValue: $this->exchangeValue
        );

        $this->exchangeValueLogistic = Logistic::where('invoice_item_id', $this->invoiceItem->id)->first()->exchangevalue ?? null;
        if ($this->exchangeValueLogistic != null) {
            $this->exchangeValue = $this->exchangeValueLogistic;
            $this->price = Logistic::where('invoice_item_id', $this->invoiceItem->id)->first()->price;
        }

        for ($i = 0; $i < $this->count; $i++) {
            $logistic = Logistic::create([
                'status_id' => 1,
                'invoice_item_id' => $this->invoiceItem->id,
                'exchangevalue' => $this->exchangeValue,
                'exchangeRealValue' => $this->exchangeRealValue,
                'transportprice' => $cal['shipBroker'],
                'itemprice' => $cal['buyCost'],
                'brokerwageprice' => $cal['buyProfit'],
                'singleItemfullprice' => $cal['buyCost'] + $cal['buyProfit'] + $cal['shipBroker'],
                'issensitive' => $this->issensitive ?? false,
                'source_id' => $this->source_id,
                'account_id' => $this->account_id,
                'base_id' => $this->base_id,
                'orderId' => $this->orderId,
                'account_discount' => $this->account_discount,
                'estimateddatetobase' => $this->estimateddatetobase,
                'price' => $this->price,
                'iscancel' => false,
                'buyingdate' => now(),
                'orderexpireddate' => now(),
                'isdeclare' => false,
                'transitionmode' => 'Continue',
                'logisticstatus' => 'Normal',
                'agent_id' => auth()->id(),
                'exchange_id' => $this->invoiceItem->exchange_id,
                'invoice_id' => $this->invoiceItem->invoice_id,
            ]);

            if (filled($this->description)) {
                InvoiceDescription::create([
                    'status_id' => 1,
                    'invoice_id' => $this->invoiceItem->invoice_id,
                    'invoice_item_id' => $this->invoiceItem->id,
                    'logistics_id' => $logistic->id,
                    'description' => $this->description,
                    'level' => 'buy-invoice',
                    'agent_id' => auth()->id(),
                ]);
            }
        }

        $comment = 'invoice-code: ' . $this->invoiceItem->invoice->code;
        if (filled($this->account_discount))
            $comment .= PHP_EOL . 'تخفیف: ' . $this->account_discount;

        SourcePaylog::create([
            'amount' => ($this->price - $this->account_discount) * $this->count * -1,
            'exchange_value' => $this->invoiceItem->exchange->value,
            'rial_value' => $this->invoiceItem->exchange->value * $this->price * $this->count,
            'log_date' => now(),
            'comment' => $comment,
            'source_id' => $this->source_id,
        ]);

        Notification::make()->title('اطلاعات خرید با موفقیت ذخیره شد.')->success()->send();
        //$this->dispatch('reRender');
        return redirect(request()->header('Referer'));
    }

    public function reject()
    {
        if (!filled($this->suspended_reason)) {
            Notification::make()->title('دلیل کنسلی الزامی میباشد.')->danger()->send();
            return;
        } elseif ($this->invoiceItem->logistics->count() + (int)$this->count > $this->invoiceItem->count) {
            Notification::make()->title('تعداد خرید از تعداد درخواستی بیشتر است.')->danger()->send();
            return;
        } elseif (!filled($this->price)) {
            Notification::make()->title('تعیین قیمت الزامی است.')->danger()->send();
            return;
        } elseif (!filled($this->count)) {
            Notification::make()->title('تعیین تعداد الزامی است.')->danger()->send();
            return;
        }

        $cal = Calculator::singleProduct(
            price: $this->price,
            massUnit: 'گرم',
            exchangeType: $this->invoiceItem->exchange_id,
            region: $this->invoiceItem->region_id,
            weight: $this->invoiceItem->firstweight
        );

        for ($i = 0; $i < $this->count; $i++) {
            $logistic = Logistic::create([
                'transportprice' => $cal['shipBroker'],
                'invoice_item_id' => $this->invoiceItem->id,
                'itemprice' => $cal['buyCost'] + $cal['buyProfit'],
                'exchangevalue' => $cal['exchangeValue'],
                'brokerwageprice' => $cal['buyProfit'],
                'singleItemfullprice' => $cal['buyCost'] + $cal['buyProfit'] + $cal['shipBroker'],
                'issensitive' => $this->issensitive ?? false,
                'source_id' => $this->source_id,
                'account_id' => $this->account_id,
                'base_id' => $this->base_id,
                'orderId' => null,
                'account_discount' => $this->account_discount,
                'estimateddatetobase' => null,
                'price' => $this->price,
                'iscancel' => true,
                'buyingdate' => now(),
                'orderexpireddate' => now(),
                'isdeclare' => false,
                'transitionmode' => 'Cancel',
                'logisticstatus' => 'Cancel',
                'agent_id' => auth()->id(),
                'exchange_id' => $this->invoiceItem->exchange_id,
                'invoice_id' => $this->invoiceItem->invoice_id,
                'isrebuy' => false,
                'isTransitionAccessToCustomer' => true,

                'canceldate' => now(),
                'transitionreason' => $this->suspended_reason,
                'canceltype' => 'توسط ما',
                'cancelreason' => $this->description,
            ]);

            if (filled($this->description)) {
                InvoiceDescription::create([
                    'invoice_id' => $this->invoiceItem->invoice_id,
                    'invoice_item_id' => $this->invoiceItem->id,
                    'logistics_id' => $logistic->id,
                    'description' => $this->description,
                    'level' => 10,
                    'agent_id' => auth()->id(),
                ]);
            }

            TransitionHistory::create([
                'last_buy_date' => now(),
                'transition_agent_id' => auth()->id(),
                'transition_date' => now(),
                'transition_reason' => $this->suspended_reason,
                'transition_action' => 'toCancelAction',
                'logistic_id' => $logistic->id
            ]);
        }

        //todo: send sms

        Notification::make()->title('کالا با موفقیت کنسل شد.')->warning()->send();
        $this->dispatch('reRender');
    }

    public function render(): View
    {
        return view('filament.buy-invoice.components.buy-item-row');
    }

}
