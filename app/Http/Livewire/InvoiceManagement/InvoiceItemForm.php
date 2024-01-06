<?php

namespace App\Http\Livewire\InvoiceManagement;

use App\Helpers\Calculator;
use App\Helpers\Convertors;
use App\Models\Exchanges;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\InvoicesOthercosts;
use App\Models\Region;
use App\Models\Weight;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Component;


class InvoiceItemForm extends Component
{
    public $invoiceItem;
    public $invoiceId;
    public $incoiceItemCount;
    public $exchanges;
    public $othercostTypes;
    public $weights;
    public $regions;

    public $name;
    public $link;
    public $count;
    public $cost;
    public $firstweight;
    public $massUnit;
    public $prefactorcomment;
    public $description;
    public $unapprovedescription;
    public $region_id;
    public $exchange_id;
    public $breakable;
    public $isauction;

    protected $rules = [
        'link' => 'required',
        'cost' => 'required',
        'count' => 'required|numeric|min:1',
        'name' => 'required',
        'firstweight' => 'required|numeric|min:1',
        'region_id' => 'required',
    ];

    public function mount(InvoiceItem $invoiceItem, $invoiceId, $incoiceItemCount): void
    {
        $this->invoiceItem = $invoiceItem;
        $this->invoiceId = $invoiceId;
        $this->incoiceItemCount = $incoiceItemCount;
        $this->exchanges = Exchanges::all();
        $this->weights = Weight::all();
        $this->regions = Region::all();
        $this->loadDataFromDatabase();
    }

    public function loadDataFromDatabase(): void
    {
        $this->name = $this->invoiceItem->name;
        $this->link = $this->invoiceItem->link;
        $this->count = $this->invoiceItem->count;
        $this->cost = $this->invoiceItem->cost;
        $this->firstweight = $this->invoiceItem->firstweight;
        $this->massUnit = 'گرم';
        $this->prefactorcomment = $this->invoiceItem->prefactorcomment;
        $this->description = $this->invoiceItem->description;
        $this->unapprovedescription = $this->invoiceItem->unapprovedescription;
        $this->region_id = $this->invoiceItem->region_id;
        $this->exchange_id = $this->invoiceItem->exchange_id;
        $this->breakable = $this->invoiceItem->breakable;
        $this->isauction = $this->invoiceItem->isauction;
    }

    public function submit(): void
    {
        $this->validate();
        $cal = Calculator::singleProduct(
            $this->cost,
            $this->massUnit,
            $this->exchange_id,
            $this->region_id,
            $this->firstweight,
            $this->count,
        );

        $invoiceItemModel = [
            "name" => $this->name,
            "count" => $this->count,
            "cost" => $this->cost,
            "firstweight" => Convertors::weightConverter($this->massUnit, $this->firstweight),
            "exchange_id" => $this->exchange_id,
            "isauction" => $this->isauction,
            'breakable' => $this->breakable,
            "region_id" => $this->region_id,
            "link" => $this->link,
            "unapprovedescription" => $this->unapprovedescription,
            "prefactorcomment" => $this->prefactorcomment,
            "ischecked" => true,
            "isapproved" => empty($this->unapprovedescription),
            "exchangevalue" => $cal['exchangeValue'],
            "brokerwageprice" => $cal['buyProfit'],
            "ItemPrice" => ($cal['buyCost']),
            "transportprice" => $cal['shipBroker'],
            "singleitemfullprice" => (double)$cal['finalResult'] / $cal['count'],
            "AdditionalPerRow" => $cal['additionalPerRow'],
            "buybroker" => $cal['bBroker'],
            "eachkgvalue" => $cal['eachKGValue'],
            "minweightval" => $cal['minWeightVal'],
        ];

        InvoiceItem::where('id', $this->invoiceItem->id)->update($invoiceItemModel);

        $price = (int)InvoiceItem::where('invoice_id', $this->invoiceItem->invoice_id)->approved()->sum('itemprice');
        $transportPrice = (int)InvoiceItem::where('invoice_id', $this->invoiceItem->invoice_id)->approved()->sum('transportprice');
        $brokerwageprice = (int)InvoiceItem::where('invoice_id', $this->invoiceItem->invoice_id)->approved()->sum('brokerwageprice');
        $sumOtherCost = InvoicesOthercosts::where('invoice_id', $this->invoiceItem->invoice_id)->sum('OtherCostPrice');
        Invoice::where('id', $this->invoiceId)->update([
            'totaltransportprice' => $transportPrice,
            'totalitemprice' => $price + $brokerwageprice,
            'totalTax' => round(($transportPrice + $price + $brokerwageprice + $sumOtherCost) * 0.09, -1),
        ]);

        Notification::make()->title('کالا با موفقیت تایید شد.')->success()->send();

        $this->dispatch('reRender');
    }

    public function reject(): void
    {
        $this->validate([
            'unapprovedescription' => 'required',
        ]);
        InvoiceItem::where('id', $this->invoiceItem->id)->update([
            "ischecked" => true,
            "isapproved" => false,
            "unapprovedescription" => $this->unapprovedescription,
        ]);

        Notification::make()->title('کالا رد شد.')->danger()->send();

        $this->dispatch('reRender');
    }

    public function render(): View
    {
        return view('filament.invoice-management.components.invoice-item-form');
    }
}
