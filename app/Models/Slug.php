<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;
use Route;
use Session;

class Slug extends Model
{
    use HasFactory;
    protected $table = 'slugs';

    public function getdatatable()
    {
        // ccd($employee_list);
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'slugs.id',
            1 => 'slugs_category.category',
            2 => 'slugs.slug',
            3 => DB::raw("CONCAT(u1.first_name,' ',u1.last_name)"),
            4 => DB::raw("CONCAT(u2.first_name,' ',u2.last_name)"),
            5 => DB::raw('(CASE WHEN slugs.status = "A" THEN "Actived" ELSE "Deactived" END)'),

        );
        $query = Slug::from('slugs')
            ->join("slugs_category", "slugs_category.id", "=", "slugs.category")
            ->join("users as u1", "u1.id", "=", "slugs.add_by")
            ->join("users as u2", "u2.id", "=", "slugs.updated_by")
            ->where("slugs.is_deleted", "=", "N");

        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $searchVal = $requestData['search']['value'];
            $query->where(function ($query) use ($columns, $searchVal, $requestData) {
                $flag = 0;
                foreach ($columns as $key => $value) {
                    $searchVal = $requestData['search']['value'];
                    if ($requestData['columns'][$key]['searchable'] == 'true') {
                        if ($flag == 0) {
                            $query->where($value, 'like', '%' . $searchVal . '%');
                            $flag = $flag + 1;
                        } else {
                            $query->orWhere($value, 'like', '%' . $searchVal . '%');
                        }
                    }
                }
            });
        }

        $temp = $query->orderBy($columns[$requestData['order'][0]['column']], $requestData['order'][0]['dir']);

        $totalData = count($temp->get());
        $totalFiltered = count($temp->get());

        $resultArr = $query->skip($requestData['start'])
            ->take($requestData['length'])
            ->select( 'slugs.id', 'slugs_category.category', 'slugs.slug', DB::raw("CONCAT(u1.first_name,' ',u1.last_name) as addBy"), DB::raw("CONCAT(u2.first_name,' ',u2.last_name) as updatedBy"), 'slugs.status')
            ->get();

        $data = array();
        $i = 0;

        foreach ($resultArr as $row) {

            $actionhtml  = '';
            $actionhtml .= '<a href="' . route('slug.edit', $row['id']) . '" class="btn btn-icon"><i class="fa fa-edit text-warning"> </i></a>';
            if ($row['status'] == 'A') {
                $status = '<span class="label label-lg label-light-success label-inline">Active</span>';
                $actionhtml .= '<a href="#" data-toggle="modal" data-target="#deactiveModel" class="btn btn-icon  deactive-records" data-id="' . $row["id"] . '" ><i class="fa fa-times text-primary" ></i></a>';
            } else {
                $status = '<span class="label label-lg label-light-danger  label-inline">Deactive</span>';
                $actionhtml .= '<a href="#" data-toggle="modal" data-target="#activeModel" class="btn btn-icon  active-records" data-id="' . $row["id"] . '" ><i class="fa fa-check text-primary" ></i></a>';
            }
            $actionhtml .= '<a href="#" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon  delete-records" data-id="' . $row["id"] . '" ><i class="fa fa-trash text-danger" ></i></a>';

            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            $nestedData[] = $row['category'];
            $nestedData[] = $row['slug'];
            $nestedData[] = $row['addBy'];
            $nestedData[] = $row['updatedBy'];
            $nestedData[] = $status;
            $nestedData[] = $actionhtml;
            $data[] = $nestedData;
        }
        $json_data = array(
            "draw" => intval($requestData['draw']), // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
            "recordsTotal" => intval($totalData), // total number of records
            "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
            "data" => $data   // total data array
        );
        return $json_data;
    }

    public function saveAdd($requestData)
    {
        $countSlug = Slug::from('slugs')
            ->where('slugs.category', $requestData['category'])
            ->where('slugs.slug', $requestData['slug'])
            ->where('slugs.is_deleted', 'N')
            ->count();
        if ($countSlug == 0) {
            $loginUser = Session::all();
            $objSlug = new Slug();
            $objSlug->category = $requestData['category'];
            $objSlug->slug = $requestData['slug'];
            $objSlug->add_by = $loginUser['logindata'][0]['id'];
            $objSlug->updated_by = $loginUser['logindata'][0]['id'];
            $objSlug->status = $requestData['status'];
            $objSlug->is_deleted = 'N';
            $objSlug->created_at = date('Y-m-d H:i:s');
            $objSlug->updated_at = date('Y-m-d H:i:s');
            if ($objSlug->save()) {
                $currentRoute = Route::current()->getName();
                unset($requestData['_token']);
                $objAudittrails = new Audittrails();
                $objAudittrails->add_audit('Insert', str_replace(".", "/", $currentRoute), json_encode($requestData->input()), 'Slugs');
                return 'added';
            }
            return 'wrong';
        }
        return 'slug_exists';
    }
    public function saveEdit($requestData)
    {
        $countSlug = Slug::from('slugs')
            ->where('slugs.category', $requestData['category'])
            ->where('slugs.slug', $requestData['slug'])
            ->where('slugs.id', '!=', $requestData['editId'])
            ->where('slugs.is_deleted', 'N')
            ->count();
        if ($countSlug == 0) {
            $loginUser = Session::all();
            $objSlug = Slug::find($requestData['editId']);
            $objSlug->category = $requestData['category'];
            $objSlug->slug = $requestData['slug'];
            $objSlug->updated_by = $loginUser['logindata'][0]['id'];
            $objSlug->status = $requestData['status'];
            $objSlug->updated_at = date('Y-m-d H:i:s');
            if ($objSlug->save()) {
                $currentRoute = Route::current()->getName();
                unset($requestData['_token']);
                $objAudittrails = new Audittrails();
                $objAudittrails->add_audit('Update', str_replace(".", "/", $currentRoute), json_encode($requestData->input()), 'Slugs');
                return 'added';
            }
            return 'wrong';
        }
        return 'slug_exists';
    }

    public function get_slug_details($slugId)
    {
        return Slug::from('slugs')
            ->select('slugs.id', 'slugs.category', 'slugs.slug', 'slugs.status')
            ->where('slugs.id', $slugId)
            ->first();
    }

    public function common_activity($requestData)
    {

        $objSlug = Slug::find($requestData['id']);
        if ($requestData['activity'] == 'delete-records') {
            $objSlug->is_deleted = "Y";
            $event = 'Delete Records';
        }

        if ($requestData['activity'] == 'active-records') {
            $objSlug->status = "A";
            $event = 'Active Records';
        }

        if ($requestData['activity'] == 'deactive-records') {
            $objSlug->status = "I";
            $event = 'Deactive Records';
        }

        $objSlug->updated_at = date("Y-m-d H:i:s");
        if ($objSlug->save()) {
            $currentRoute = Route::current()->getName();
            unset($requestData['_token']);
            $objAudittrails = new Audittrails();
            $res = $objAudittrails->add_audit($event, str_replace(".", "/", $currentRoute), json_encode($requestData), 'Slugs');
            return true;
        } else {
            return false;
        }
    }

    public function get_slug_by_category($category)
    {
        return Slug::from('slugs')->select('slugs.id', 'slugs.slug')
            ->where('slugs.category', $category)
            ->where('slugs.is_deleted', 'N')
            ->where('slugs.status', 'A')
            ->get()->toArray();
    }
}
