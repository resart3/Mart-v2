<?php

namespace App\Http\Controllers\Views;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Statistic;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statistic = new Statistic;
        if (session()->get('user')->role == 'admin_rt') {
            $title = "Statistik RT ".session()->get('user')->rt_rw;
            $var = session()->get('user')->rt_rw;
        }
        else {
            $title = "Statistik RW ".explode("/",session()->get('user')->rt_rw)[1];
            $var = explode("/",session()->get('user')->rt_rw)[1];
        }
        $data = array (
            'krt' => $statistic->countKRT($var),
            'male' => $statistic->countMale($var),
            'female' => $statistic->countFemale($var),
            'lansia' => $statistic->countLansia($var),
            'wanitaSubur' => $statistic->wanitaSubur($var),
            'usia12_18' => $statistic->usia12_18($var),
            'usia6_12' => $statistic->usia6_12($var),
            'usia0_6' => $statistic->usia0_6($var),
        );

        return view('statistik.statistik', compact('data', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
