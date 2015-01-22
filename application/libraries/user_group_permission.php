<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

Class User_group_permission {
    var $permission = array();
    function getPermissionCheckBoxes($currentPermissionArray)
    {
        $checkboxlist = "";
        
        $parray = $this->get_permissions_array();
        foreach($parray as $key => $val):
            if($key == "DELIMITER"):    
                $checkboxlist .="<br clear='all'/>";
            elseif(substr($key,0,6) == "HEADER"):
                $checkboxlist .="<h3>".$val."</h3>";
            else:
                $checked = (isset($currentPermissionArray[$key]))?"checked":"";            
                $checkboxlist .= '<input type="checkbox" '.$checked.' name="permissions['.$key.']" value="'.$val.'" /> '.$val.'<br />';
            endif;
            
        endforeach;
        
        return $checkboxlist;
    }
    
    function convert_permission_to_array($permissionString)
    {
        $pstring = explode(";",$permissionString);
        
        $parray = array();
        foreach($pstring as $key => $val)
        {
            $parray[$val] = $val;
        }
        array_pop($parray);
        return $parray;
        
    }
    
    function convert_permission_to_string($permission)
    {
        $pstring = "";
        if(is_array($permission)):
            foreach($permission as $key => $val)
            {
                $pstring .= $val.";";
            }            
        endif;
        
        return $pstring;
    }
    
    function get_permissions_array()
    {   
        $permission = $this->permission;
        
        $permission = $this->get_menu_array();


        
        $permission['HEADER_Users'] = "Users";
        $permission['canViewUser'] = "canViewUser";
        $permission['canAddUser'] = "canAddUser";
        $permission['canEditUser'] = "canEditUser";
        $permission['canDeleteUser'] = "canDeleteUser";
        $permission['DELIMITER'] = true;
        
        $permission['HEADER_User_Groups'] = "User Groups";
        $permission['canViewUserGroup'] = "canViewUserGroup";
        $permission['canAddUserGroup'] = "canAddUserGroup";
        $permission['canEditUserGroup'] = "canEditUserGroup";
        $permission['canDeleteUserGroup'] = "canDeleteUserGroup";
        $permission['canSetUserGroupPermission'] = "canSetUserGroupPermission";        
        $permission['DELIMITER'] = true;
		
        $permission['HEADER_Vendors'] = "Vendors";
        $permission['canViewVendors'] = "canViewVendors";
        $permission['canAddVendors'] = "canAddVendors";
        $permission['canEditVendors'] = "canEditVendors";
        $permission['canDeleteVendors'] = "canDeleteVendors";
        $permission['DELIMITER'] = true;
		
        $permission['HEADER_Units'] = "Units";
        $permission['canViewUnits'] = "canViewUnits";
        $permission['canAddUnits'] = "canAddUnits";
        $permission['canEditUnits'] = "canEditUnits";
        $permission['canDeleteUnits'] = "canDeleteUnits";
        $permission['DELIMITER'] = true;
		
        $permission['HEADER_Categories'] = "Categories";
        $permission['canViewCategories'] = "canViewCategories";
        $permission['canAddCategories'] = "canAddCategories";
        $permission['canEditCategories'] = "canEditCategories";
        $permission['canDeleteCategories'] = "canDeleteCategories";
        $permission['DELIMITER'] = true;
		
        $permission['HEADER_Item_Master'] = "Item Master";
        $permission['canViewItem_Master'] = "canViewItem_Master";
        $permission['canAddItem_Master'] = "canAddItem_Master";
        $permission['canEditItem_Master'] = "canEditItem_Master";
        $permission['canDeleteItem_Master'] = "canDeleteItem_Master";
        $permission['DELIMITER'] = true;
		
        $permission['HEADER_Organizations'] = "Organizations";
        $permission['canViewOrganizations'] = "canViewOrganizations";
        $permission['canAddOrganizations'] = "canAddOrganizations";
        $permission['canEditOrganizations'] = "canEditOrganizations";
        $permission['canDeleteOrganizations'] = "canDeleteOrganizations";
        $permission['DELIMITER'] = true;
		
        $permission['HEADER_Departments'] = "Departments";
        $permission['canViewDepartments'] = "canViewDepartments";
        $permission['canAddDepartments'] = "canAddDepartments";
        $permission['canEditDepartments'] = "canEditDepartments";
        $permission['canDeleteDepartments'] = "canDeleteDepartments";
        $permission['DELIMITER'] = true;
		
        $permission['HEADER_Requisitions'] = "Requisitions";
        $permission['canViewRequisitions'] = "canViewRequisitions";
        $permission['canAddRequisitions'] = "canAddRequisitions";
        $permission['canEditRequisitions'] = "canEditRequisitions";
        $permission['canApproveRequisitions'] = "canApproveRequisitions";
        $permission['canDeleteRequisitions'] = "canDeleteRequisitions";
        $permission['DELIMITER'] = true;
		
        $permission['HEADER_Quotations'] = "Quotations";
        $permission['canViewQuotations'] = "canViewQuotations";
        $permission['canAddQuotations'] = "canAddQuotations";
        $permission['canEditQuotations'] = "canEditQuotations";
        $permission['canDeleteQuotations'] = "canDeleteQuotations";
        $permission['canApproveQuotations'] = "canApproveQuotations";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Receives'] = "Receives";
        $permission['canViewReceives'] = "canViewReceives";
        $permission['canAddReceives'] = "canAddReceives";
        $permission['canEditReceives'] = "canEditReceives";
        $permission['canDeleteReceives'] = "canDeleteReceives";
       // $permission['canApproveReceives'] = "canApproveReceives";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Stock'] = "Stock";
        $permission['canEditStock'] = "canEditStock";
        $permission['canDeleteStock'] = "canDeleteStock";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Issue'] = "Issue";
        $permission['canAddIssue'] = "canAddIssue";
        $permission['canEditIssue'] = "canEditIssue";
        $permission['canDeleteIssue'] = "canDeleteIssue";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Repair_Type'] = "Repair Type";
        $permission['canViewRepair_Type'] = "canViewRepair_Type";
        $permission['canAddRepair_Type'] = "canAddRepair_Type";
        $permission['canEditRepair_Type'] = "canEditRepair_Type";
        $permission['canDeleteRepair_Type'] = "canDeleteRepair_Type";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Damage'] = "Damage";
        $permission['canViewDamage'] = "canViewDamage";
        $permission['canAddDamage'] = "canAddDamage";
        $permission['canEditDamage'] = "canEditDamage";
        $permission['canDeleteDamage'] = "canDeleteDamage";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Repair'] = "Repair";
        $permission['canViewRepair'] = "canViewRepair";
        $permission['canAddRepair'] = "canAddRepair";
        $permission['canEditRepair'] = "canEditRepair";
        $permission['canDeleteRepair'] = "canDeleteRepair";
        $permission['canCompleteRepair'] = "canCompleteRepair";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Budget'] = "Budget";
        $permission['canViewBudget'] = "canViewBudget";
        $permission['canAddBudget'] = "canAddBudget";
        $permission['canEditBudget'] = "canEditBudget";
        $permission['canDeleteBudget'] = "canDeleteBudget";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Bill'] = "Bill";
        $permission['canViewBill'] = "canViewBill";
        $permission['canAddBill'] = "canAddBill";
        $permission['canEditBill'] = "canEditBill";
        $permission['canDeleteBill'] = "canDeleteBill";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Companies'] = "Companies";
        $permission['canViewCompanies'] = "canViewCompanies";
        $permission['canAddCompanies'] = "canAddCompanies";
        $permission['canEditCompanies'] = "canEditCompanies";
        $permission['canDeleteCompanies'] = "canDeleteCompanies";
        $permission['DELIMITER'] = true;

        $permission['HEADER_Designation'] = "Designation";
        $permission['canViewDesignation'] = "canViewDesignation";
        $permission['canAddDesignation'] = "canAddDesignation";
        $permission['canEditDesignation'] = "canEditDesignation";
        $permission['canDeleteDesignation'] = "canDeleteDesignation";
        $permission['DELIMITER'] = true;

        return $permission;
    }
    
    function get_menu_array()
    {
        $permission = $this->permission;
        $permission['HEADER_Menu_Assignment'] = "Menu Assigment";
        /*$permission['canViewHomeMenu'] = "canViewHomeMenu";
        $permission['canViewSetupMenu'] = "canViewSetupMenu";
        //$permission['canViewPatientMenu'] = "canViewPatientMenu";
        $permission['canViewReportMenu'] = "canViewReportMenu";
        
        $permission['DELIMITER'] = true;*/
		
        $permission['canViewIT-InventoryMenu'] = "canViewIT-InventoryMenu";
        $permission['canViewAdmin-SetupMenu'] = "canViewAdmin-SetupMenu";
        //$permission['canViewPatientMenu'] = "canViewPatientMenu";
        //$permission['canViewReportMenu'] = "canViewReportMenu";
        
        $permission['DELIMITER'] = true;
		
        return $permission;
    }
    
    
    
}

?>