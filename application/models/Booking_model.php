<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Booking_model (Booking Model)
 * Booking model class to get to handle booking related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Booking_model extends CI_Model
{
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function bookingListingCount($searchText)
    {
        $this->db->select('*');
        $this->db->from('tbl_booking as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    function delete($userId, $userInfo)
    {
        $this->db->where('bookingId', $userId);
        $this->db->update('tbl_booking', $userInfo);
        
        return $this->db->affected_rows();
    }


    
    /**
     * This function is used to get the booking listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function bookingListing($searchText, $page, $segment)
    {
        $this->db->select('*');
        $this->db->from('tbl_booking as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.name LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by('BaseTbl.bookingId', 'DESC');
          $this->db->limit(5000);
        $query = $this->db->get(); 
      
        $result = $query->result();        
        return $result;
    }
    

    function getRandomAllData($where = null)
    {
        $this->db->select('*');
        $this->db->from('tbl_booking');
        $this->db->where('isDeleted', 0);
        $this->db->where('assign_to', 0);
        $this->db->order_by('RAND()');
        $query = $this->db->get();
        $result = $query->row();    
        return $result;
    }
 

    /**
     * This function is used to add new booking to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewBooking($bookingInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_booking', $bookingInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get booking information by id
     * @param number $bookingId : This is booking id
     * @return array $result : This is booking information
     */
    function getBookingInfo($bookingId)
    {
        $this->db->select('bookingId, name, description');
        $this->db->from('tbl_booking');
        $this->db->where('bookingId', $bookingId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the booking information
     * @param array $bookingInfo : This is booking updated information
     * @param number $bookingId : This is booking id
     */
    function editBooking($bookingInfo, $bookingId)
    {
        $this->db->where('bookingId', $bookingId);
        $this->db->update('tbl_booking', $bookingInfo);
        
        return TRUE;
    }
}