<?php

namespace App\Http\Controllers;

use Auth;
use App\Province;
use App\Regency;
use App\School;
use App\Pic;
use App\SchoolLevel;
use App\SchoolStatus;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSchool;
use Validator;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;

class SchoolController extends Controller
{
    private $createdMessage;
    private $updatedMessage;
    private $noPermission;
    private $table;
    private $policeNumbers;
    private $departments;
    private $isoCertificates;
    private $references;

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->createdMessage = 'Data successfully created.';
        $this->updatedMessage = 'Data successfully updated';
        $this->noPermission = 'You have no related permission.';
        $this->table = 'schools';
        $this->policeNumbers = ['A' => 'A', 'AA' => 'AA', 'AB' => 'AB', 'AD' => 'AD', 'AE' => 'AE', 'AG' => 'AG', 'B' => 'B', 'BA' => 'BA', 'BB' => 'BB', 'BD' => 'BD', 'BE' => 'BE', 'BG' => 'BG', 'BH' => 'BH', 'BK' => 'BK', 'BL' => 'BL', 'BM' => 'BM', 'BN' => 'BN', 'BP' => 'BP', 'D' => 'D', 'DA' => 'DA', 'DB' => 'DB', 'DC' => 'DC', 'DD' => 'DD', 'DE' => 'DE', 'DF' => 'DF', 'DG' => 'DG', 'DH' => 'DH', 'DK' => 'DK', 'DL' => 'DL', 'DM' => 'DM', 'DN' => 'DN', 'DP' => 'DP', 'DR' => 'DR', 'DS' => 'DS', 'DT' => 'DT', 'DW' => 'DW', 'E' => 'E', 'EA' => 'EA', 'EB' => 'EB', 'ED' => 'ED', 'F' => 'F', 'G' => 'G', 'H' => 'H', 'K' => 'K', 'KB' => 'KB', 'KH' => 'KH', 'KT' => 'KT', 'KU' => 'KU', 'L' => 'L', 'M' => 'M', 'N' => 'N', 'P' => 'P', 'PB' => 'PB', 'R' => 'R', 'S' => 'S', 'T' => 'T', 'V' => 'V', 'W' => 'W', 'X' => 'X', 'Z' => 'Z'];
        $this->departments = ['Teknik Komputer dan Jaringan', 'Rekayasa Perangkat Lunak', 'Multimedia', 'Animasi', 'Broadcasting', 'Teknik Audio dan Video', 'Teknik Elektronika', 'Teknik Elektronika dan Industri', 'Teknik Sepeda Motor', 'Teknik Kendaraan Ringan', 'Teknik Gambar Bangunan', 'Administrasi Perkantoran', 'Pemasaran', 'Keuangan/Perbankan', 'Farmasi', 'Akuntansi', 'Other'];
        $this->isoCertificates = ['Sudah' => 'Sudah', 'Dalam Proses (persiapan dokumen / pembentukan team audit internal / pendampingan)' => 'Dalam Proses (persiapan dokumen / pembentukan team audit internal / pendampingan)', 'Belum' => 'Belum'];
        $this->references = ['Sekolah Peserta / Sekolah Binaan', 'Dealer', 'Internet (Facebook Page/Web)', 'Lain-Lain'];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school = School::find(Auth::user()->school->id);
        $view = [
            'title' => 'School Detail',
            'breadcrumbs' => [
                route('school.index') => 'School',
                null => 'Detail'
            ],
            'provinces' => Province::pluck('name', 'name')->toArray(),
            'regencies' => Regency::getByProvinceName($school->province)->pluck('name', 'name')->toArray(),
            'policeNumbers' => $this->policeNumbers,
            'departments' => $this->departments,
            'isoCertificates' => $this->isoCertificates,
            'references' => $this->references,
            'school' => $school
        ];
        return view('school.show', $view);
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
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function show(School $school)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function edit(School $school)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, School $school)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\School  $school
     * @return \Illuminate\Http\Response
     */
    public function destroy(School $school)
    {
        //
    }
}
