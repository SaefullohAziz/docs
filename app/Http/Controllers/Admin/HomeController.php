<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Island;
use App\Province;
use App\SchoolLevel;
use App\SchoolStatus;
use App\Department;
use App\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = [
            'title' => __('Home'),
            'schoolComments' => SchoolLevel::orderBy('created_at', 'asc')->get()->each(function ($schoolComment) {
                $schoolComment->load(['schoolComments' => function ($query) {
                    $query->latest('created_at')->limit(20);
                }]);
            }),
            'statusMovements' => SchoolLevel::orderBy('created_at', 'asc')->get()->each(function ($statusMovement) {
                $statusMovement->load(['statusUpdates' => function ($query) {
                    $query->limit(20);
                }, 'statusUpdates.school', 'statusUpdates.status', 'statusUpdates.staff']);
            }),
            'schoolStatuses' => SchoolLevel::with(['statuses.schools'])->orderBy('created_at', 'asc')->get()->toArray(),
            'schoolFtps' => [
                [
                    'name' => 'Candidate',
                    'schools' => School::whereHas('statusUpdate.status', function ($query) {
                        $query->whereIn('name', ['Disetujui Potensi Level B', 'Konfirmasi Persetujuan Sudah Dikirim', 'Hardcopy CL Diterima']);
                    })->whereRaw('NOT EXISTS (
                        SELECT 1 FROM subsidies 
                        JOIN schools AS s ON subsidies.school_id = s.id 
                        JOIN school_status_updates ON school_status_updates.id = (
                            SELECT school_status_updates.id FROM school_status_updates 
                            JOIN school_statuses ON school_status_updates.school_status_id = school_statuses.id 
                            WHERE school_status_updates.school_id = schools.id 
                            AND school_statuses.name NOT IN ("Disetujui Potensi Level B", "Konfirmasi Persetujuan Sudah Dikirim", "Hardcopy CL Diterima") 
                            ORDER BY school_status_updates.created_at DESC LIMIT 1)
                        WHERE s.id = schools.id 
                            AND subsidies.id = "ACP Getting started Pack (AGP) / Fast Track Program (FTP)" 
                            AND subsidies.created_at > school_status_updates.created_at
                            AND NOT EXISTS (SELECT 1 FROM school_status_updates 
                                JOIN school_statuses ON school_status_updates.school_status_id = school_statuses.id 
                                WHERE school_statuses.name = "No Respond CL" 
                                AND STR_TO_DATE(school_status_updates.created_at, "%Y-%m-%d") >= (CURDATE() + INTERVAL 1 MONTH )
                            )
                        )')->limit(5)->get()->toArray(),
                ],
                [
                    'name' => 'Submission',
                    'schools' => School::whereHas('subsidies', function ($query) {
                        $query->where('type', 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)')->whereHas('subsidyStatus.status', function ($query) {
                            $query->whereIn('name', ['Created', 'Processed']);
                        })->orderBy('created_at', 'desc');
                    })->limit(5)->get()->toArray(),
                ],
                [
                    'name' => 'Approved',
                    'schools' => School::whereHas('subsidies', function ($query) {
                        $query->where('type', 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)')->whereMonth('created_at', '>=', date('m', strtotime('-1 month')))->whereHas('subsidyStatus.status', function ($query) {
                            $query->where('name', 'Approved');
                        })->orderBy('created_at', 'desc');
                    })->limit(5)->get()->toArray(),
                ],
                [
                    'name' => 'Paid',
                    'schools' => School::whereHas('subsidies', function ($query) {
                        $query->where('type', 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)')->whereHas('subsidyStatus.status', function ($query) {
                            $query->where('name', 'Paid');
                        })->orderBy('created_at', 'desc');
                    })->limit(5)->get()->toArray(),
                ],
                [
                    'name' => 'Expired',
                    'schools' => School::whereHas('subsidies', function ($query) {
                        $query->where('type', 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)')->whereMonth('created_at', '<', date('m', strtotime('-1 month')))->whereHas('subsidyStatus.status', function ($query) {
                            $query->where('name', 'Approved');
                        })->orderBy('created_at', 'desc');
                    })->limit(5)->get()->toArray(),
                ],
                [
                    'name' => 'Rejected',
                    'schools' => School::whereHas('subsidies', function ($query) {
                        $query->where('type', 'ACP Getting started Pack (AGP) / Fast Track Program (FTP)')->whereHas('subsidyStatus.status', function ($query) {
                            $query->where('name', 'Reject');
                        })->orderBy('created_at', 'desc');
                    })->limit(5)->get()->toArray(),
                ],
            ],
            'schoolPerProvince' => Province::withCount('schools')->get()->toArray(),
            'islands' => Island::pluck('name', 'id')->toArray(),
            'provinces' => Province::pluck('name', 'id')->toArray(),
            'levels' => SchoolLevel::pluck('name', 'id')->toArray(),
            'statuses' => SchoolStatus::pluck('name', 'id')->toArray(),
            'studentPerDepartment' => Department::withCount('students')->get()->toArray(),
            'departments' => Department::pluck('name', 'id')->toArray(),
        ];
        return view('admin.home.index', $view);
    }
}
