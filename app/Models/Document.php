<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Session;
use Route;
use DB;
class Document extends Model
{
    use HasFactory;
    protected $table = 'document';

    public function getdatatable()
    {
        // ccd($employee_list);
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'document.id',
            1 => 'document.title',
            2 => 'slugs.slug',
            3 => 'document.topic',
            4 => DB::raw('DATE_FORMAT(document.updated_at, "%d-%b-%Y")'),
            5 => DB::raw("CONCAT(u1.first_name,' ',u1.last_name)"),
            6 => DB::raw("CONCAT(u2.first_name,' ',u2.last_name)"),
            7 => DB::raw('(CASE WHEN document.status = "A" THEN "Actived" ELSE "Deactived" END)'),
        );
        $query = Slug::from('document')
            ->join("slugs", "slugs.id", "=", "document.subTitle")
            ->join("users as u1", "u1.id", "=", "document.add_by")
            ->join("users as u2", "u2.id", "=", "document.updated_by")
            ->where("document.is_deleted", "=", "N");

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
            ->select('document.id', 'document.title', 'slugs.slug', 'document.topic', DB::raw('DATE_FORMAT(document.updated_at, "%d-%b-%Y") as updated_at'), DB::raw("CONCAT(u1.first_name,' ',u1.last_name) as addBy"), DB::raw("CONCAT(u2.first_name,' ',u2.last_name) as updatedBy"), 'document.status')
            ->get();

        $data = array();
        $i = 0;

        foreach ($resultArr as $row) {

            $actionhtml  = '';
            $actionhtml .= '<a href="' . route('document.generate-pdf', $row['id']) . '" class="btn btn-icon"><i class="fa fa-file-pdf-o text-info"> </i></a>';
            $actionhtml .= '<a href="' . route('document.edit', $row['id']) . '" class="btn btn-icon"><i class="fa fa-edit text-warning"> </i></a>';
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
            $nestedData[] = '<label class="checkbox checkbox-lg">
            <input type="checkbox" data-id="' . $row["id"] . '" class="single-check" >
            <span></span></label>';
            $nestedData[] = $row['title'];
            $nestedData[] = $row['slug'];
            $nestedData[] = $row['topic'];
            $nestedData[] = date_formate($row['updated_at']);
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
        $loginUser = Session::all();
        $objSlug = new Document();
        $objSlug->title = $requestData['title'];
        $objSlug->subTitle = $requestData['subTitle'];
        $objSlug->topic = $requestData['topic'];
        $objSlug->subTopic = $requestData['subTopic'];
        $objSlug->content = $requestData['content'];
        $objSlug->reference = $requestData['reference'];
        $objSlug->status = $requestData['status'];
        $objSlug->add_by = $loginUser['logindata'][0]['id'];
        $objSlug->updated_by = $loginUser['logindata'][0]['id'];
        $objSlug->is_deleted = 'N';
        $objSlug->created_at = date('Y-m-d H:i:s');
        $objSlug->updated_at = date('Y-m-d H:i:s');
        if ($objSlug->save()) {
            $currentRoute = Route::current()->getName();
            unset($requestData['_token']);
            $objAudittrails = new Audittrails();
            $objAudittrails->add_audit('Insert', str_replace(".", "/", $currentRoute), json_encode($requestData->input()), 'Documents');
            return 'added';
        }
        return 'wrong';
    }

    public function saveEdit($requestData)
    {

        $loginUser = Session::all();
        $objSlug = Document::find($requestData['editId']);
        $objSlug->title = $requestData['title'];
        $objSlug->subTitle = $requestData['subTitle'];
        $objSlug->topic = $requestData['topic'];
        $objSlug->subTopic = $requestData['subTopic'];
        $objSlug->content = $requestData['content'];
        $objSlug->reference = $requestData['reference'];
        $objSlug->status = $requestData['status'];
        $objSlug->updated_by = $loginUser['logindata'][0]['id'];
        $objSlug->updated_at = date('Y-m-d H:i:s');
        if ($objSlug->save()) {
            $currentRoute = Route::current()->getName();
            unset($requestData['_token']);
            $objAudittrails = new Audittrails();
            $objAudittrails->add_audit('Update', str_replace(".", "/", $currentRoute), json_encode($requestData->input()), 'Documents');
            return 'added';
        }
        return 'wrong';
    }

    public function get_document_details($id)
    {
        return Document::from('document')
            ->join('slugs', 'slugs.id', '=', 'document.subTitle')
            ->join('slugs_category', 'slugs_category.id', '=', 'slugs.category')
            ->select('slugs_category.id as categoryId', 'slugs.slug', 'slugs_category.category','document.title', 'document.subTitle', 'document.status', 'document.topic', 'document.subTopic', 'document.content', 'document.reference', 'document.id')
            ->where('document.id', $id)
            ->first();
    }

    public function common_activity($requestData){

        $objDocument = Document::find($requestData['id']);
        if($requestData['activity'] == 'delete-records'){
            $objDocument->is_deleted = "Y";
            $event = 'Delete Records';
        }

        if($requestData['activity'] == 'active-records'){
            $objDocument->status = "A";
            $event = 'Active Records';
        }

        if($requestData['activity'] == 'deactive-records'){
            $objDocument->status = "I";
            $event = 'Deactive Records';
        }

        $objDocument->updated_at = date("Y-m-d H:i:s");
        if($objDocument->save()){
            $currentRoute = Route::current()->getName();
            unset($requestData['_token']);
            $objAudittrails = new Audittrails();
            $res = $objAudittrails->add_audit($event, str_replace(".", "/", $currentRoute) , json_encode($requestData) , 'Documents');
            return true;
        }else{
            return false ;
        }
    }
}
