<?php

namespace App\Http\Livewire\InvoiceManagement;

use App\Helpers\Calculator;
use App\Models\Exchanges;
use App\Models\Invoice;
use App\Models\InvoicesOthercosts;
use App\Models\OthercostType;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Notifications\Notification;

class AddOtherCost extends Component
{
    public $exchanges;
    public $othercostTypes;

    public $otherCostType;
    public $otherCostExchange;
    public $otherCost;
    public $otherCostComment;
    public $invoiceId;
    public $regionId;

    protected $rules = [
        'otherCostType' => 'required',
        'otherCostExchange' => 'required',
        'otherCost' => 'required',
    ];

    public function mount($invoiceId, $regionId): void
    {
        $this->invoiceId = $invoiceId;
        $this->regionId = $regionId;
        $this->exchanges = Exchanges::all();
        $this->othercostTypes = OthercostType::all();
    }

    public function render(): View
    {
        return view('filament.invoice-management.components.add-other-cost');
    }

    public function addOtherCost()
    {
        $this->validate();

        $exchange = Exchanges::where('id', $this->otherCostExchange)->value('value');

        InvoicesOthercosts::create([
            'type_id' => $this->otherCostType,
            'amount' => $this->otherCost,
            'OtherCostPrice' => $this->otherCost * $exchange,
            'comment' => $this->otherCostComment,
            'invoice_id' => $this->invoiceId,
            'exchange_id' => $this->otherCostExchange,
            'level' => 1,
        ]);

        $sumOtherCost = InvoicesOthercosts::where('invoice_id', $this->invoiceId)->sum('OtherCostPrice');

        $invoice = Invoice::find($this->invoiceId);

        Invoice::where('id', $this->invoiceId)->update([
            'othercosts' => $sumOtherCost,
            'totalTax' => round(($invoice->totaltransportprice + $invoice->totalitemprice + $sumOtherCost) * 0.09, -1),
        ]);

        Notification::make()
            ->title('هزینه اضافی با موفقیت اضافه شد.')
            ->success()
            ->send();

        $this->dispatch('reRender');
    }
}
