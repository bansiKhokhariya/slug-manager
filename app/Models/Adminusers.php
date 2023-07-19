<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Audittrails;
use DB;
use Route;
use Session;
use Hash;

class Adminusers extends Model
{
    use HasFactory;
    protected $table= "users";

    public function getdatatable()
    {
        $requestData = $_REQUEST;
        $columns = array(
            0 => 'users.id',
            1 => 'users.first_name',
            2 => 'users.last_name',
            3 => 'users.email',
            4 => DB::raw('DATE_FORMAT(users.created_at, "%d-%b-%Y")'),
            5 => DB::raw('(CASE WHEN users.status = "A" THEN "Actived" ELSE "Deactived" END)'),

        );
        $query = Adminusers ::from('users')
                ->where("users.id", "!=", Auth()->guard('admin')->user()['id'])
                ->where("users.user_type", "=", "A")
                ->where("users.is_deleted", "=", "N");

        if (!empty($requestData['search']['value'])) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
            $searchVal = $requestData['search']['value'];
            $query->where(function($query) use ($columns, $searchVal, $requestData) {
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
                    ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', DB::raw('DATE_FORMAT(users.created_at, "%d-%b-%Y") as created_date'), 'users.status')
                    ->get();

        $data = array();
        $i = 0;

        foreach ($resultArr as $row) {
            $actionhtml  = '';
            $actionhtml .= '<a href="' . route('admin-users.edit', $row['id']) . '" class="btn btn-icon"><i class="fa fa-edit text-warning"> </i></a>';
            if($row['status'] == 'A'){
                $status = '<span class="label label-lg label-light-success label-inline">Active</span>';
                $actionhtml .= '<a href="#" data-toggle="modal" data-target="#deactiveModel" class="btn btn-icon  deactive-records" data-id="' . $row["id"] . '" ><i class="fa fa-times text-primary" ></i></a>';
            }else{
                $status = '<span class="label label-lg label-light-danger  label-inline">Deactive</span>';
                $actionhtml .= '<a href="#" data-toggle="modal" data-target="#activeModel" class="btn btn-icon  active-records" data-id="' . $row["id"] . '" ><i class="fa fa-check text-primary" ></i></a>';
            }
            $actionhtml .= '<a href="#" data-toggle="modal" data-target="#deleteModel" class="btn btn-icon  delete-records" data-id="' . $row["id"] . '" ><i class="fa fa-trash text-danger" ></i></a>';
            $i++;
            $nestedData = array();
            $nestedData[] = $i;
            // $nestedData[] = $row['id'];
            $nestedData[] = $row['first_name'];
            $nestedData[] = $row['last_name'];
            $nestedData[] = $row['email'];
            $nestedData[] = $row['created_date'];
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

    public function saveAdd($requestData){
        $checkUsersMail = Adminusers::where('users.email', $requestData['email'])
                                ->where('users.is_deleted', 'N')
                                ->count();
        if($checkUsersMail == 0){
            $checkUsersPhoneNumber = Adminusers::where('users.phone_number', $requestData['phone_number'])
                        ->where('users.phone_number', '!=', null)
                        ->where('users.is_deleted', 'N')
                        ->count();

            if($checkUsersPhoneNumber == 0){
                $objUsers = new Adminusers();
                $objUsers->first_name = $requestData['first_name'];
                $objUsers->last_name = $requestData['last_name'];
                $objUsers->email  = $requestData['email'];
                $objUsers->phone_number  = $requestData['phone_number'];
                $objUsers->email_verified_at = null;
                $objUsers->password = Hash::make($requestData['password']);
                $objUsers->userimage = null;
                $objUsers->user_type = 'A';
                $objUsers->status = $requestData['status'];
                $objUsers->is_deleted = 'N';
                $objUsers->created_at = date('Y-m-d H:i:s');
                $objUsers->updated_at = date('Y-m-d H:i:s');
                if($objUsers->save()){
                    $currentRoute = Route::current()->getName();
                    unset($requestData['_token']);
                    unset($requestData['password']);
                    unset($requestData['new_confirm_password']);
                    $objAudittrails = new Audittrails();
                    $res = $objAudittrails->add_audit('Insert', str_replace(".", "/", $currentRoute) , json_encode($requestData->input()) , 'Admin users');
                    return 'added';
                }else{
                    return 'wrong';
                }
            }
            return 'user_phone_number_exists';
        }
        return 'user_mail_exists';
    }

    public function saveEdit($requestData){
        $checkUsersMail = Adminusers::where('users.email', $requestData['email'])
                                ->where('users.id', '!=', $requestData['editId'])
                                ->where('users.is_deleted', 'N')
                                ->count();
        if($checkUsersMail == 0){
            $checkUsersPhoneNumber = Adminusers::where('users.phone_number', $requestData['phone_number'])
                        ->where('users.phone_number', '!=', null)
                        ->where('users.id', '!=', $requestData['editId'])
                        ->where('users.is_deleted', 'N')
                        ->count();

            if($checkUsersPhoneNumber == 0){
                $objUsers = Adminusers::find($requestData['editId']);
                $objUsers->first_name = $requestData['first_name'];
                $objUsers->last_name = $requestData['last_name'];
                $objUsers->email  = $requestData['email'];
                $objUsers->phone_number  = $requestData['phone_number'];
                $objUsers->status = $requestData['status'];
                $objUsers->updated_at = date('Y-m-d H:i:s');
                if($objUsers->save()){
                    $currentRoute = Route::current()->getName();
                    unset($requestData['_token']);
                    $objAudittrails = new Audittrails();
                    $res = $objAudittrails->add_audit('Update', str_replace(".", "/", $currentRoute) , json_encode($requestData->input()) , 'Admin users');
                    return 'added';
                }else{
                    return 'wrong';
                }
            }
            return 'user_phone_number_exists';
        }
        return 'user_mail_exists';
    }

    public function get_admin_users_details($userId){
        return Adminusers::from('users')
            ->select('users.id', 'users.first_name', 'users.last_name', 'users.email', 'users.phone_number', 'users.status')
            ->where('users.id', $userId)->get()->toArray();
    }

    public function common_activity($requestData){

        $objAdminusers = Adminusers::find($requestData['id']);
        if($requestData['activity'] == 'delete-records'){
            $objAdminusers->is_deleted = "Y";
            $event = 'Delete Records';
        }

        if($requestData['activity'] == 'active-records'){
            $objAdminusers->status = "A";
            $event = 'Active Records';
        }

        if($requestData['activity'] == 'deactive-records'){
            $objAdminusers->status = "I";
            $event = 'Deactive Records';
        }

        $objAdminusers->updated_at = date("Y-m-d H:i:s");
        if($objAdminusers->save()){
            $currentRoute = Route::current()->getName();
            unset($requestData['_token']);
            $objAudittrails = new Audittrails();
            $res = $objAudittrails->add_audit($event, str_replace(".", "/", $currentRoute) , json_encode($requestData) , 'Admin users');
            return true;
        }else{
            return false ;
        }
    }
}
