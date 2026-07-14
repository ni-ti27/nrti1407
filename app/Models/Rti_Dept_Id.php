<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rti_Dept_Id extends Model
{
    use HasFactory;

    protected $table = 'rti_dept_id';

     /**
     * createDeptCode function
     *
     * @param [string] $deptId
     * @param [string] $officeId
     * @param [string] $subofficeId
     * @return string
     */
    public static function createDeptCode($deptId, $officeId, $subofficeId)
    {
        $dept = ($deptId < 10) ? '0' . $deptId : $deptId;
        $office = ($officeId < 10) ? '0' . $officeId : $officeId;
        $subOffice = ($subofficeId < 10) ? '0' . $subofficeId : $subofficeId;
        $dcode = $dept . '' . $office . '' . $subOffice;

        $deptDetails = DepartmentView::where('dept_id', $dcode)->first();
        if ($deptDetails) {
            return $deptDetails;
        }
        return null;
    }

    /**
     * Retrieves the department name by its code.
     *
     * @param int $deptId The ID of the department.
     * @param int $officeId The ID of the office.
     * @param int $subofficeId The ID of the suboffice.
     * @throws Some_Exception_Class A description of the exception that can be thrown.
     * @return string|null The department name if found, otherwise null.
     */
    public static function getDepartmentNameByCode($deptId, $officeId, $subofficeId)
    {
        $formattedDeptId = ($deptId < 10) ? '0' . $deptId : $deptId;
        $formattedOfficeId = ($officeId < 10) ? '0' . $officeId : $officeId;
        $formattedSubOfficeId = ($subofficeId < 10) ? '0' . $subofficeId : $subofficeId;
        $dcode = $formattedDeptId . '' . $formattedOfficeId . '' . $formattedSubOfficeId;

        $deptDetails = DepartmentView::where('dept_id', $dcode)->first();
        dd($deptDetails);
        if ($deptDetails) {
            return $deptDetails->department;
        }

        // Consider handling cases where department details are not found
        // Perhaps log an error or throw an exception
        // return null; // Keeping the existing behavior for not found scenario
    }

}
