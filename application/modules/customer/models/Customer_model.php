<?php defined('BASEPATH') or exit('No direct script access allowed');

class Customer_model extends MY_Model
{
    // set public if want to override per_page
    public $per_page;

    public function validateModalAdd()
    {
        $data = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('name') == '')
        {
            $data['inputerror'][] = 'error-name';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('phone-number') == '')
        {
            $data['inputerror'][] = 'error-phone-number';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('type') == '')
        {
            $data['inputerror'][] = 'error-type';
            $data['status'] = FALSE;
        }
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    public function validateModalEdit()
    {
        $data = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;
 
        if($this->input->post('edit-name') == '')
        {
            $data['inputerror'][] = 'error-edit-name';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('edit-phone-number') == '')
        {
            $data['inputerror'][] = 'error-edit-phone-number';
            $data['status'] = FALSE;
        }
 
        if($this->input->post('edit-type') == '')
        {
            $data['inputerror'][] = 'error-edit-type';
            $data['status'] = FALSE;
        }
 
        if($data['status'] === FALSE)
        {
            echo json_encode($data);
            exit();
        }
    }

    public function filter_data($filters, $page = null)
    {
        $customers = $this->select(['customer_id', 'name', 'address', 'phone_number', 'type'])
            ->when('keyword', $filters['keyword'])
            ->when('type', $filters['type'])
            ->order_by('name')
            ->paginate($page)
            ->get_all();

        $total = $this->select('customer_id', 'name')
            ->when('keyword', $filters['keyword'])
            ->when('type', $filters['type'])
            ->order_by('customer_id')
            ->count();

        return [
            'customers'  => $customers,
            'total' => $total
        ];
    }

    public function when($params, $data)
    {
        // jika data null, maka skip
        if ($data != '') {
            if ($params == 'keyword') {
                $this->group_start();
                $this->or_like('name', $data);
                $this->or_like('address', $data);
                $this->or_like('phone_number', $data);
                $this->group_end();
            } else {
                $this->group_start();
                $this->or_like('type', $data);
                $this->group_end();
            }
        }
        return $this;
    }
}
