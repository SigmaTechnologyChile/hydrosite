<?php

namespace App\Exports;

use App\Models\Reading;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReadingsHistoryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        
        return Reading::join('services', 'readings.service_id', 'services.id')
            ->join('members', 'services.member_id', 'members.id')
            ->leftJoin('locations', 'services.locality_id', 'locations.id') 
            ->select(
                "readings.id as lec",  
                "readings.period",  
                "services.sector as location_name", 
                \DB::raw("LPAD(services.nro, 5, '0') as nro_servicio"),
                "members.rut as rut",
                "members.full_name as cliente",  
                "readings.period as period_copy",
                \DB::raw("CONCAT(readings.previous_reading, ' / ', readings.current_reading) as lecturas"),  
                \DB::raw("readings.current_reading - readings.previous_reading as consumo_m3"),  
                \DB::raw("readings.vc_water as valor_consumo_agua")
            )
            ->get();
    }

    public function headings(): array
    {
        return [
            "Lec.", 
            "Periodo", 
            "Sector", 
            "N° Servicio",
            "RUT",
            "Cliente", 
            "Fecha", 
            "Lecturas (Ant / Act)",
            "Consumo (M³)", 
            "Valor Consumo Agua"
        ];
    }
}