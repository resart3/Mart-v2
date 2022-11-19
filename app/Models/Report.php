<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    use HasFactory;

    // public function getAllReportLunas($data){
    //     $report = DB::table('transactions')
    //         ->join('family_cards', 'family_cards.nomor', '=', 'transactions.family_card_id')
    //         ->where([
    //             ['transactions.bulan', '=', $data['bulan']],
    //             ['transactions.tahun', '=', $data['tahun']],
    //             ])
    //         ->groupBy('family_cards.rt_rw')
    //         ->select('family_cards.rt_rw', DB::raw('SUM(transactions.jumlah) as jumlah'))
    //         ->get();
    //     return $report;
    // }

    public function getAllRTRW(){
        $data = DB::table('family_cards')
        ->select('rt_rw')
        ->distinct('rt_rw')
        ->get();

        return $data;
    }

    public function getAllRt($rw){
        $rt = DB::table('family_cards')
        ->where('rt_rw', 'LIKE', '%'.$rw)
        ->groupBy('rt_rw')
        ->select('rt_rw')
        ->get();
        return $rt;
    }

    public function lunas_report_all($data){
        $report = DB::table('transactions')
            ->join('family_cards', 'family_cards.nomor', '=', 'transactions.family_card_id')
            ->where([
                ['transactions.bulan', '=', $data['bulan']],
                ['transactions.tahun', '=', $data['tahun']],
                ])
            ->groupBy('family_cards.rt_rw')
            ->select('family_cards.rt_rw', DB::raw('SUM(transactions.jumlah) as jumlah'))
            ->get();
        return $report;
    }

    public function lunas_report($data){
        $report = DB::table('transactions')
            ->join('family_cards', 'family_cards.nomor', '=', 'transactions.family_card_id')
            ->where([
                ['family_cards.rt_rw', 'like', "%".$data['rw']],
                ['transactions.bulan', '=', $data['bulan']],
                ['transactions.tahun', '=', $data['tahun']],
                ])
            ->groupBy('family_cards.rt_rw')
            ->select('family_cards.rt_rw', DB::raw('SUM(transactions.jumlah) as jumlah'))
            ->get();
        return $report;
    }

    public function detail_lunas($data){
        $report = DB::table('transactions')
            ->join('family_cards', 'family_cards.nomor', '=', 'transactions.family_card_id')
            ->join('family_members', 'family_cards.nomor', '=', 'family_members.family_card_id')
            ->where([
                ['family_cards.rt_rw', '=', $data['rt_rw']],
                ['transactions.bulan', '=', $data['bulan']],
                ['transactions.tahun', '=', $data['tahun']],
                ['family_members.isFamilyHead', '=', 1],
                ])
            ->select('family_cards.nomor', 'family_cards.rt_rw', 'family_members.nama', 'transactions.jumlah')
            ->get();
        return $report;
    }

    public function tunggakan_report($data){
        $report = DB::table('lands')
        ->join('family_cards', 'family_cards.nomor', '=', 'lands.family_card_id')
        ->join('categories', 'categories.id', '=', 'lands.category_id')
        ->where('family_cards.rt_rw', 'LIKE', "%".$data['rt_rw'])
        ->whereNotIn('family_cards.nomor', $this->subquery_tunggakan($data))
        ->groupBy('family_cards.rt_rw')
        ->select('family_cards.rt_rw', DB::raw('SUM(categories.amount) as jumlah'))
        ->get();
        return $report;
    }

    public function tunggakan_report_all($data){
        $report = DB::table('lands')
        ->join('family_cards', 'family_cards.nomor', '=', 'lands.family_card_id')
        ->join('categories', 'categories.id', '=', 'lands.category_id')
        ->whereNotIn('family_cards.nomor', $this->subquery_tunggakan_all($data))
        ->groupBy('family_cards.rt_rw')
        ->select('family_cards.rt_rw', DB::raw('SUM(categories.amount) as jumlah'))
        ->get();
        return $report;
    }

    public function detail_tunggakan($data){
        $report = DB::table('lands')
        ->join('family_cards', 'family_cards.nomor', '=', 'lands.family_card_id')
        ->join('categories', 'categories.id', '=', 'lands.category_id')
        ->join('family_members', 'family_cards.nomor', '=', 'family_members.family_card_id')
        ->where('family_cards.rt_rw', '=', $data['rt_rw'])
        ->where('family_members.isFamilyHead', '=' ,1)
        ->whereNotIn('family_cards.nomor', $this->subquery_tunggakan($data))
        ->select('family_members.nama', 'family_cards.nomor','family_cards.rt_rw', DB::raw('categories.amount as jumlah'))
        ->get();
        return $report;
    }

    function subquery_tunggakan($data){
        $query =  DB::table('transactions')
        ->join('family_cards', 'family_cards.nomor', '=', 'transactions.family_card_id')
        ->where([
            ['family_cards.rt_rw', 'LIKE', "%".$data['rt_rw']],
            ['transactions.bulan', '=', $data['bulan']],
            ['transactions.tahun', '=', $data['tahun']],
            ])
        ->select('family_cards.nomor')
        ->get();
        $data_return = [];
        foreach ($query as $value) {
            $data_return[] = $value->nomor;
        }

        return $data_return;
    }

    function subquery_tunggakan_all($data){
        $query =  DB::table('transactions')
        ->join('family_cards', 'family_cards.nomor', '=', 'transactions.family_card_id')
        ->where([
            ['transactions.bulan', '=', $data['bulan']],
            ['transactions.tahun', '=', $data['tahun']],
            ])
        ->select('family_cards.nomor')
        ->get();
        $data_return = [];
        foreach ($query as $value) {
            $data_return[] = $value->nomor;
        }

        return $data_return;
    }
}