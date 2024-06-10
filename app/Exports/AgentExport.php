<?php

namespace App\Exports;
use App\Models\Order;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\Exportable;
    
    class AgentExport implements FromCollection,WithHeadings,WithMapping,ShouldAutoSize
    {
        /**
        * @return \Illuminate\Support\Collection
        */
       
        use Exportable;
    
        protected $id;
        protected $from_date;
        protected $to_date;
    
        function __construct($id,$from_date,$to_date) {
                $this->id = $id;
                $this->from_date = $from_date;
                $this->to_date = $to_date;
        }

    public function collection()
    {
    
       return Order::where('shop_id',current_shop_id())->where('agent_id',$this->id)->whereBetween('created_at',[ $this->from_date,$this->to_date])->with('payment_method','gateway','agent_details','agent_avathar_details')->withCount('order_item')->orderBy('id')->get();
    }

    public function payment_status($status)

    {

    switch ($status) {
        case "0":
            $status = "Cancelled";
            break;
        case "1":
            $status = "Completed";
            break;
        case "2":
            $status = "Pending";
            break;
        case "3":
            $status = "Incomplete";
            break;
        default:
            $status = "No Status Found";
        
        }
        return $status;
    }

    public function map($data) : array {
        
    $data->payment_status = $this->payment_status($data->payment_status); 
    
    return [
            
            
            $data->order_no,
            $data->agent_details->first_name,
            $data->agent_details->last_name,
            $data->agent_details->email,
            $data->agent_avathar_details->agent_id,
            $data->total,
            $data->order_item_count,
            $data->gateway->name ?? '',
            $data->payment_status,
            $data->status,
            $data->created_at->format('d-F-Y')

    ] ;


}

public function headings():array{
    return[
        
        'Invoice ID',
        'Name',
        'Last Name',
        'Email',
        'Agent_Prof',
        'Amount',
        'Items',
        'Payment Method',
        'Payment Status',
        'Order Status',
        'Due Date'
    ];
} 

}

