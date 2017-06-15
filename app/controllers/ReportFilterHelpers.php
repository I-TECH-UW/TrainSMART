<?php

require_once ('app/controllers/ITechController.php');

class ReportFilterHelpers extends ITechController
{

    /**
     * Get the location filter values
     *
     * @param array $criteria = array() - extra criteria to pass through, matching keys get overwritten
     * @param string $prefix = '' - prefix of html element parameter names
     *
     * @return array - $criteria(with city, city_parent_id), location tier, location id
     */
    protected function getLocationCriteriaValues($criteria = array(), $prefix = '')
    {
        $params = $this->getAllParams();
        require_once('views/helpers/Location.php');

        return getCriteriaValues($params, $this->_countrySettings, $criteria, $prefix);
    }

    // helper to generate a where clause based on 9 available levels of regions in $criteria
    // return value: " `$tablePrefix`.region_X_id IN (val, val, val) ";
    protected function getLocationCriteriaWhereClause(&$criteria, $prefix = '', $tablePrefix = '')
    {

        $tableCols = array('', 'province_id', 'district_id', 'region_c_id', 'region_d_id', 'region_e_id', 'region_f_id', 'region_g_id', 'region_h_id', 'region_i_id');
        list($crit, $tier, $location_id) = $this->getLocationCriteriaValues($criteria, $prefix, $tablePrefix);

        if ($prefix)
            $prefix = $prefix . "_";

        $tableString = $tablePrefix ? "$tablePrefix." : '';

        if ($location_id)
            $location_id = $this->_array_me($location_id); // sometimes $location_id is a string because it comes from a form input[]

        if ($tier && !empty ($location_id))
            return " " . $tableString . $tableCols[$tier] . " IN ( " . implode(',', $location_id) . " ) ";

        return false;
    }

    //_is_filter_all
    // is string (pass) or array with out "" (pass)
    // "" is used for --ALL-- in drop downs, now we are supporting multi-value <selects>
    protected function _is_filter_all($arrOrStr)
    {
        if (is_array($arrOrStr)) {
            if (!in_array("", $arrOrStr))
                return false;
            else
                return true;
        } else if ($arrOrStr || $arrOrStr === '0') {  //not really used, should be safe though
            return false;
        } else if ($arrOrStr == "") {
            return true;
        }
        return false;
    }

    protected function _is_not_filter_all($arrOrStr)
    {
        return (!$this->_is_filter_all($arrOrStr));
    }

    /**
     * returns an array of partner ids that the logged in user can edit
     * @return array
     */
    public function getAvailablePartners()
    {
        $db = $this->dbfunc();
        $user_id = $this->isLoggedIn();
        if ($this->hasACL('training_organizer_option_all')) {
            $sql = 'SELECT id from partner';
        } else {
            $sql = 'SELECT partner.id FROM partner ' .
                'INNER JOIN training_organizer_option ON partner.organizer_option_id = training_organizer_option.id ' .
                'INNER JOIN user_to_organizer_access ON ' .
                'user_to_organizer_access.training_organizer_option_id = training_organizer_option.id ' .
                "WHERE user_id = $user_id";
        }
        $editablePartners = $db->fetchCol($sql);

        return $editablePartners;
    }

    /**
     * gets an associative array of partners by id - filtered by user access
     * @return array
     */
    public function getAvailablePartnersAssoc()
    {
        $db = $this->dbfunc();
        $uid = $this->isLoggedIn();
        if ($this->hasACL('training_organizer_option_all')) {
            $select = $db->select()->from('partner', array('id', 'partner'));
        }
        else {
            $select = $db->select()
                ->from('partner', array('id', 'partner'))
                ->joinInner('user_to_organizer_access',
                    'partner.organizer_option_id = user_to_organizer_access.training_organizer_option_id', array())
                ->where('user_to_organizer_access.user_id = ?', $uid);
        }
        $partners = $db->fetchAssoc($select);
        return $partners;

    }

    /**
     * Creates a data dump in a CSV format. Using a real, tested 3rd-party CSV library that handles edge cases and
     * UTF-8 would be better. This code was pulled from the preservice reports .phtml files and tightened up a bit
     *
     * @param $headers - the names that appear as the first row
     * @param $content - array of arrays(rows) for the result
     * @return array|string - a string of newline-and-comma-separated values
     */
    public static function generateCSV($headers, $content) {
        $data = array();

        if ($headers[0] === 'ID') {
            // work around excel problem - https://support.microsoft.com/en-us/help/323626/-sylk-file-format-is-not-valid-error-message-when-you-open-file

            $headers[0] = "id";
        }
        $data[] = array_values($headers);

        foreach ($content as $row) {
            $data[] = array_values($row);
        }

        $delimiter = ',';
        $enclosure = '"';
        $encloseAll = false;
        $nullToMysqlNull = false;

        $delimiter_esc = preg_quote($delimiter, '/');
        $enclosure_esc = preg_quote($enclosure, '/');

        $output = array();

        foreach ($data as $row){
            $outputrow = array();
            foreach ($row as $field){
                if ($field === null && $nullToMysqlNull) {
                    $outputrow[] = 'NULL';
                    continue;
                }

                // Enclose fields containing $delimiter, $enclosure or whitespace
                if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
                    $outputrow[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
                }
                else {
                    $outputrow[] = $field;
                }
            }
            $output[] = implode($delimiter,$outputrow);
        }
        $output = implode("\n", $output);

        return $output;
    }

    /**
     * Disable .phtml rendering process for CSV files that use ZF's ContextSwitch functionality
     * used by CSV ContextSwitch object to provide generic CSV output
     */
    public function preCsvCallback() {

        // See ZendFramework/library/Zend/Controller/Action/Helper/ContextSwitch.php::initJsonContext() to see how to do this with Zend Framework static methods
        if ($this->view instanceof Zend_View_Interface) {
            $this->setNoRenderer();
        }
    }

    /**
     * used by ZF's ContextSwitch functionality to generate CSV output files
     * relies on view object to have data members called 'headers' and 'output' containing data for CSV output
     * @throws Zend_Controller_Action_Exception
     */
    public function postCsvCallback() {

        // See ZendFramework/library/Zend/Controller/Action/Helper/ContextSwitch.php::postJsonContext() to see how to do this with Zend Framework static methods

        if ($this->view instanceof Zend_View_Interface) {
            if (property_exists($this->view, 'headers') && property_exists($this->view, 'output')) {

                // set the output file name
                $fn = $this->getRequest()->getActionName() . "-" . date('Y-m-d.H.m.s') . '.csv';
                $response = $this->getResponse();
                $response->setHeader('Content-Disposition', 'attachment; filename=' . $fn);

                $output = $this->generateCSV($this->view->headers, $this->view->output);
                $response->setBody($output);
            } else {
                /**
                 * @see Zend_Controller_Action_Exception
                 */
                require_once 'Zend/Controller/Action/Exception.php';
                throw new Zend_Controller_Action_Exception('Missing required data member "headers" or "output" for CSV output on view object.');

            }
        } else {
            /**
             * @see Zend_Controller_Action_Exception
             */
            require_once 'Zend/Controller/Action/Exception.php';
            throw new Zend_Controller_Action_Exception('Unexpected class used for CSV output.');

        }
    }


    /**
     * @param $params - query criteria
     * @return array containing a Zend_Db_Select object and the column headers for output
     */

    protected function psStudentReportsBuildQuery(&$params) {

        $headers = array();
        $headers[] = "First Name";
        $headers[] = "Last Name";
        $cohortJoined = false;
        $institutionJoined = false;

        $db = Zend_Db_Table_Abstract::getDefaultAdapter();
        $helper = new Helper();

        $s = $db->select()
            ->from(array('p' => 'person'), array('p.first_name', 'p.last_name'))
            ->joinInner(array('s' => 'student'), 's.personid = p.id', array())
            ->where('p.is_deleted = 0');

        if ((isset($params['showProvince']) && $params['showProvince']) ||
            (isset($params['province_id']) && $params['province_id'])) {

            if (!$institutionJoined) {
                $s->joinLeft(array('i' => 'institution'), 'i.id = s.institutionid', array());
                $institutionJoined = true;
            }

            $s->joinLeft(array('loc1' => 'location'), 'loc1.id = i.geography1', array());

            if (isset($params['showProvince']) && $params['showProvince']) {
                $headers[] = t("Region A (Province)");
                $s->columns('loc1.location_name');
            }
            if (isset($params['province_id']) && $params['province_id']) {
                $s->where('loc1.id IN (?)', $params['province_id']);
            }
        }

        if ((isset($params['showDistrict']) && $params['showDistrict']) ||
            (isset($params['district_id']) && $params['district_id'])) {
            if (!$institutionJoined) {
                $s->joinLeft(array('i' => 'institution'), 'i.id = s.institutionid', array());
                $institutionJoined = true;
            }

            $s->joinLeft(array('loc2' => 'location'), 'loc2.id = i.geography2', array());

            if (isset($params['showDistrict']) && $params['showDistrict']) {
                $headers[] = t("Region B (Health District)");
                $s->columns('loc2.location_name');
            }
            if (isset($params['district_id']) && $params['district_id']) {
                $ids = "";
                foreach ($params['district_id'] as $l) {
                    $ids .= array_pop(explode('_', $l)) .", ";
                }
                $ids = trim($ids, ', ');
                $s->where('loc2.id IN (?)', $ids);
            }
        }

        if ((isset($params['showRegionC']) && $params['showRegionC']) ||
            (isset($params['region_c_id']) && $params['region_c_id'])) {
            if (!$institutionJoined) {
                $s->joinLeft(array('i' => 'institution'), 'i.id = s.institutionid', array());
                $institutionJoined = true;
            }

            $s->joinLeft(array('loc3' => 'location'), 'loc3.id = i.geography3', array());

            if (isset($params['showRegionC']) && $params['showRegionC']) {
                $headers[] = t("Region C (Local Region)");
                $s->columns('loc3.location_name');
            }
            if (isset($params['region_c_id']) && $params['region_c_id']) {
                $ids = "";
                foreach ($params['region_c_id'] as $l) {
                    $ids .= array_pop(explode('_', $l)) .", ";
                }
                $ids = trim($ids, ', ');
                $s->where('loc3.id IN (?)', $ids);
            }
        }

        if (isset($params['institution']) && $params['institution'] ||
            isset($params['showinstitution']) && $params['showinstitution']) {

            if (!$institutionJoined) {
                $s->joinLeft(array('i' => 'institution'), 'i.id = s.institutionid', array());
                $institutionJoined = true;
            }
            if (isset($params['showinstitution']) && $params['showinstitution']) {
                $headers[] = "Institution";
                $s->columns('i.institutionname');
            }
            if (isset($params['institution']) && $params['institution']) {
                $s->where('i.id = ?', $params['institution']);
            }
        }

        if (isset($params['cohort']) && $params['cohort'] ||
            isset($params['showcohort']) && $params['showcohort']) {

            $s->joinLeft(array('lsc' => 'link_student_cohort'), 'lsc.id_student = s.id', array());
            $s->joinLeft(array('c' => 'cohort'), 'c.id = lsc.id_cohort', array());
            $cohortJoined = true;
            if (isset($params['showcohort']) && $params['showcohort']) {
                $headers[] = "Cohort";
                $s->columns('c.cohortname');
            }
            if (isset($params['cohort']) && $params['cohort']) {
                $s->where('c.id = ?', $params['cohort']);
            }
            //TA  filter cohort by institution access as well
            $uid = $helper->myid();
            $user_institutions = $helper->getUserInstitutions($uid);
            if (!empty($user_institutions)) {
                $s->where("c.institutionid IN (SELECT institutionid FROM link_user_institution WHERE userid = ?)", $uid);
            }
        }

        if (isset($params['cadre']) && $params['cadre'] ||
            isset($params['showcadre']) && $params['showcadre']) {

            $s->joinLeft(array('ca' => 'cadres'), 'ca.id = s.cadre', array());
            if (isset($params['showcadre']) && $params['showcadre']) {
                $headers[] = "Cadre";
                $s->columns('ca.cadrename');
            }
            if (isset($params['cadre']) && $params['cadre']) {
                $s->where('ca.id = ?', $params['cadre']);
            }
        }

        if (isset($params['showyearinschool']) && $params['showyearinschool']) {
            if (!$cohortJoined) {
                $s->joinLeft(array('lsc' => 'link_student_cohort'), 'lsc.id_student = s.id', array());
                $s->joinLeft(array('c' => 'cohort'), 'c.id = lsc.id_cohort', array());
                $cohortJoined = true;
            }
            $s->columns('c.startdate');
            $headers[] = "Start Date";
            if (isset($params['yearinschool']) && $params['yearinschool']) {
                $s->where($db->quoteInto("c.startdate LIKE ?", substr($params['yearinschool'], 0, 4) . '%'));
            }
        }

        if (isset($params['showgender']) && $params['showgender']) {
            $s->columns('p.gender');
            $headers[] = "Gender";
        }
        if (isset($params['gender']) && $params['gender']) {
            $gender_id = $params['gender'];
            if ($gender_id > 0) {
                $gender_arr = array(1 => 'male', 2 => 'female');
                $s->where('p.gender = ?', $gender_arr[$gender_id]);
            }
        }

        if ((isset($params['shownationality'])) && $params['shownationality'] ||
            (isset($params['nationality']) && $params['nationality'])) {

            $s->joinLeft(array('ln' => 'lookup_nationalities'), 'ln.id = s.nationalityid', array());
            if (isset($params['shownationality']) && $params['shownationality']) {
                $headers[] = "Nationality";
                $s->columns('ln.nationality');
            }
            if (isset($params['nationality']) && $params['nationality']) {
                $s->where('ln.id = ?', $params['nationality']);
            }
        }

        if ((isset($params['showage']) && $params['showage']) ||
            (isset($params['agemin']) && $params['agemin']) ||
            (isset($params['agemax']) && $params['agemax'])) {

            if (isset($params['showage']) && $params['showage']) {
                $headers[] = "Age";
                $s->columns(new Zend_Db_Expr("DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.birthdate)), '%Y')+0 AS age"));

                if (isset($params['agemin']) && $params['agemin']) {
                    $s->having('age >= ?', $params['agemin']);
                }
                if (isset($params['agemax']) && $params['agemax']) {
                    $s->having('age <= ?', $params['agemax']);
                }

            }
            else {
                $ageExpr = new Zend_Db_Expr("DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(p.birthdate)), '%Y')+0");
                if (isset($params['agemin']) && $params['agemin']) {
                    $s->where($ageExpr . ' >= ?', $params['agemin']);
                }
                if (isset($params['agemax']) && $params['agemax']) {
                    $s->where($ageExpr . ' <= ?', $params['agemax']);
                }
            }
        }

        if (isset($params['showactive']) && $params['showactive']) {
            $headers[] = "Active";
            $s->where("p.active = 'active'");
        }

        if (isset($params['showterminated']) && $params['showterminated']) {
            if (!$cohortJoined) {
                $s->joinLeft(array('lsc' => 'link_student_cohort'), 'lsc.id_student = s.id', array());
                $s->joinLeft(array('c' => 'cohort'), 'c.id = lsc.id_cohort', array());
                $cohortJoined = true;
            }
            $s->columns("IF(lsc.isgraduated = 0 AND lsc.dropdate != '0000-00-00', 'Terminated Early', '')");
            $headers[] = "Terminated Early";

            $s->where("lsc.isgraduated = 0");
            $s->where("lsc.dropdate != '0000-00-00'");
        }

        if ((isset($params['showdegrees'])) && $params['showdegrees'] ||
            (isset($params['degrees']) && $params['degrees'])) {

            if (!$institutionJoined) {
                $s->joinLeft(array('i' => 'institution'), 'i.id = s.institutionid', array());
                $institutionJoined = true;
            }

            $s->joinLeft(array('liddeg' => 'link_institution_degrees'), 'liddeg.id_institution = i.id', array());
            $s->joinLeft(array('ldeg' => 'lookup_degrees'), 'ldeg.id = liddeg.id_degree', array());

            if ((isset($params['showdegrees'])) && $params['showdegrees']) {
                $headers[] = "Degree";
                $s->columns('ldeg.degree');
            }
            if (isset($params['degrees']) && $params['degrees']) {
                $s->where('ldeg.id = ?', $params['degrees']);
            }
        }

        if (isset($params['showgraduated']) && $params['showgraduated']) {
            if (!$cohortJoined) {
                $s->joinLeft(array('lsc' => 'link_student_cohort'), 'lsc.id_student = s.id', array());
                $s->joinLeft(array('c' => 'cohort'), 'c.id = lsc.id_cohort', array());
                $cohortJoined = true;
            }
            $s->columns("IF(lsc.isgraduated = 1, 'Graduated', '')");
            $headers[] = "Graduated";
            $s->where("lsc.isgraduated = 1");;
        }

        if (isset($params['showfunding']) && $params['showfunding']) {
            $s->joinLeft(array('lsf' => 'link_student_funding'), 'lsf.studentid = s.id', array());
            $s->joinLeft(array('lf' => 'lookup_fundingsources'), 'lf.id = lsf.fundingsource', array());

            //TA:103 to display multiple sources for one person in one row
            $s->columns('GROUP_CONCAT(lf.fundingname)');
            $s->group('p.id');

            $headers[] = "Funding";
        }

        if ((isset($params['showfacility']) && $params['showfacility']) ||
            (isset($params['facility']) && $params['facility'])) {
            $s->joinLeft(array('lsfac' => 'link_student_facility'), 'lsfac.id_student = s.id', array());
            $s->joinLeft(array('fac' => 'facility'), 'fac.id = lsfac.id_facility', array());

            if ((isset($params['showfacility']) && $params['showfacility'])) {
                $s->columns('fac.facility_name');
                $headers[] = "Facility";
            }
            if (isset($params['facility']) && $params['facility']) {
                $s->where('fac.id = ?', $params['facility']);
            }
        }

        if ((isset($params['showtutor']) && $params['showtutor']) ||
            (isset($params['tutor']) && $params['tutor'])) {
            if (!$cohortJoined) {
                $s->joinLeft(array('lsc' => 'link_student_cohort'), 'lsc.id_student = s.id', array());
                $s->joinLeft(array('c' => 'cohort'), 'c.id = lsc.id_cohort', array());
                $cohortJoined = true;
            }
            $s->joinLeft(array('lct' => 'link_cadre_tutor'), 'lct.id_cadre = c.cadreid', array());
            $s->joinLeft(array('tut' => 'tutor'), 'tut.id = lct.id_tutor', array());
            $s->joinLeft(array('tutp' => 'person'), 'tutp.id = tut.personid', array());

            if (isset($params['tutor']) && $params['tutor']) {
                $s->where('tut.id = ?', $params['tutor']);
            }

            if (isset($params['showtutor']) && $params['showtutor']) {
                $s->columns("CONCAT(tutp.first_name,' ',tutp.last_name) AS tutor_name");
                $headers[] = "Tutor Advisor";
            }
        }

        $start_date = '';
        if ((isset($params['startday']) && $params['startday']) &&
            (isset($params['startmonth']) && $params['startmonth']) &&
            (isset($params['startyear']) && $params['startyear'])) {
            if (!$cohortJoined) {
                $s->joinLeft(array('lsc' => 'link_student_cohort'), 'lsc.id_student = s.id', array());
                $s->joinLeft(array('c' => 'cohort'), 'c.id = lsc.id_cohort', array());
                $cohortJoined = true;
            }
            $start_date = $params['startyear'].'-'.$params['startmonth'].'-'.$params['startday'];
        }

        $end_date = '';
        if ((isset($params['endday']) && $params['endday']) &&
            (isset($params['endmonth']) && $params['endmonth']) &&
            (isset($params['endyear']) && $params['endyear'])) {
            if (!$cohortJoined) {
                $s->joinLeft(array('lsc' => 'link_student_cohort'), 'lsc.id_student = s.id', array());
                $s->joinLeft(array('c' => 'cohort'), 'c.id = lsc.id_cohort', array());
                $cohortJoined = true;
            }
            $end_date = $params['endyear'].'-'.$params['endmonth'].'-'.$params['endday'];
        }

        if (($start_date !== '') || ($end_date !== '')) {
            $s->columns('c.startdate');
            $headers[] = "Start Date";
            if ($start_date !== '') {
                $s->where('c.startdate >= ?', $start_date);
            }
            if ($end_date !== '') {
                $s->where('c.startdate <= ?', $end_date);
            }
        }

        return(array($s, $headers));
    }

    protected function getCurrentQuarterStartDate() {
        $now = new DateTime();
        $thisYear = $now->format('Y');
        $quarterStarts = array(DateTime::createFromFormat('Y-m-d', $thisYear . '-01-01'),
            DateTime::createFromFormat('Y-m-d', $thisYear . '-04-01'),
            DateTime::createFromFormat('Y-m-d', $thisYear . '-07-01'),
            DateTime::createFromFormat('Y-m-d', $thisYear . '-10-01'),
        );
        $currentQuarterStartDate = $quarterStarts[0];
        $size = count($quarterStarts);
        for ($i = 0; $i < $size; $i++) {
            if (($i + 1) >= $size) {
                $currentQuarterStartDate = $quarterStarts[$i];
                break;
            }
            if ($quarterStarts[$i] < $now && $quarterStarts[$i + 1] > $now) {
                $currentQuarterStartDate = $quarterStarts[$i];
                break;
            }
        }
        return ($currentQuarterStartDate);
    }

    protected function employeeValidateCriteria($criteria) {

        if (isset($criteria['transition_start_date']) && $criteria['transition_start_date']) {
            $errorMessage = null;
            try {
                $date = DateTime::createFromFormat('d/m/Y', $criteria['transition_start_date']);
            }
            catch (Exception $e) {
                $errorMessage = t('Invalid start value for') . ' ' . t('Actual Transition Date');
            }
            // But wait, there's more! DateTime can interpret the date into a different day without
            // throwing an exception.
            if (!$errorMessage) {
                $errors = DateTime::getLastErrors();
                if (count($errors) && ((isset($errors['warning_count']) && $errors['warning_count'] > 0) ||
                        (isset($errors['error_count']) && $errors['error_count']) > 0)) {
                    $errorMessage = t('Invalid start value for') . ' ' . t('Actual Transition Date');
                }
            }
            if ($errorMessage) {
                return $errorMessage;
            }
        }

        if (isset($criteria['transition_end_date']) && $criteria['transition_end_date']) {
            $errorMessage = null;
            try {
                $date = DateTime::createFromFormat('d/m/Y', $criteria['transition_end_date']);
            }
            catch (Exception $e) {
                $errorMessage = t('Invalid end value for') . ' ' . t('Actual Transition Date');
            }

            // But wait, there's more! DateTime can interpret the date into a different day without
            // throwing an exception.
            if (!$errorMessage) {
                $errors = DateTime::getLastErrors();
                if (count($errors) && ((isset($errors['warning_count']) && $errors['warning_count'] > 0) ||
                        (isset($errors['error_count']) && $errors['error_count']) > 0)) {
                    $errorMessage = t('Invalid end value for') . ' ' . t('Actual Transition Date');
                }
            }
            if (!$errorMessage) {
                // ensure that end date is after start date, if a start date is given
                if (isset($criteria['transition_start_date']) && $criteria['transition_start_date']) {
                    // assume start date is valid if we've reached here because it was validated before this
                    $startdate = DateTime::createFromFormat('d/m/Y', $criteria['transition_start_date']);
                    if ($date <= $startdate) {
                        $errorMessage = t('Actual Transition Date') . ' ' . t('end date must be after start date.');
                    }
                }
            }

            if ($errorMessage) {
                return $errorMessage;
            }
        }

        if (isset($criteria['contract_start_date']) && $criteria['contract_start_date']) {
            $errorMessage = null;
            try {
                $date = DateTime::createFromFormat('d/m/Y', $criteria['contract_start_date']);
            }
            catch (Exception $e) {
                $errorMessage = t('Invalid value for') . ' ' . t('Contract Date');
            }
            // But wait, there's more! DateTime can interpret the date into a different day without
            // throwing an exception.
            if (!$errorMessage) {
                $errors = DateTime::getLastErrors();
                if (count($errors) && ((isset($errors['warning_count']) && $errors['warning_count'] > 0) ||
                        (isset($errors['error_count']) && $errors['error_count']) > 0)) {
                    $errorMessage = t('Invalid value for') . ' ' . t('Contract Date');
                }
            }
            if ($errorMessage) {
                return $errorMessage;
            }
        }

        if (isset($criteria['contract_end_date']) && $criteria['contract_end_date']) {
            $errorMessage = null;
            try {
                $date = DateTime::createFromFormat('d/m/Y', $criteria['contract_end_date']);
            }
            catch (Exception $e) {
                $errorMessage = t('Invalid value for') . ' ' . t('Contract End Date');
            }

            // But wait, there's more! DateTime can interpret the date into a different day without
            // throwing an exception.
            if (!$errorMessage) {
                $errors = DateTime::getLastErrors();
                if (count($errors) && ((isset($errors['warning_count']) && $errors['warning_count'] > 0) ||
                        (isset($errors['error_count']) && $errors['error_count']) > 0)) {
                    $errorMessage = t('Invalid value for') . ' ' . t('Contract End Date');
                }
            }

            if ($errorMessage) {
                return $errorMessage;
            }
        }


        if (isset($criteria['hours_min'])) {
            if (!is_numeric($criteria['hours_min'])) {
                return (t('Hours Worked per Week') . ' ' . t('must be a number'));
            }

            if (intval($criteria['hours_min']) < 0) {
                return (t('Minimum') . ' ' . t('Hours Worked per Week') . ' ' . t('must be greater than or equal to 0.'));
            }
        }
        if (isset($criteria['hours_max']) && $criteria['hours_max']) {
            if (!is_numeric($criteria['hours_max'])) {
                return (t('Hours Worked per Week') . ' ' . t('must be a number'));
            }

            if (intval($criteria['hours_max']) < 0) {
                return (t('Maximum') . ' ' . t('Hours Worked per Week') . ' ' . t('must be greater than or equal to 0.'));
            }

            if (intval($criteria['hours_max']) < intval($criteria['hours_min'])) {
                return (t('Maximum') . ' ' . t('Hours Worked per Week') . ' ' . t('must be greater than Minimum.'));
            }
        }

        if (isset($criteria['cost_min'])) {
            if (!is_numeric($criteria['cost_min'])) {
                return (t('Annual Cost') . ' ' . t('must be a number'));
            }
            if (intval($criteria['cost_min']) < 0) {
                return (t('Minimum') . ' ' . t('Annual Cost') . ' ' . t('must be greater than or equal to 0.'));
            }
        }
        if (isset($criteria['cost_max']) && $criteria['cost_max']) {
            if (!is_numeric($criteria['cost_max'])) {
                return (t('Annual Cost') . ' ' . t('must be a number'));
            }
            if (intval($criteria['cost_max']) < intval($criteria['cost_min'])) {
                return (t('Maximum') . ' ' . t('Annual Cost') . ' ' . t('must be greater than Minimum.'));
            }
            if (intval($criteria['cost_max']) < 0) {
                return (t('Maximum') . ' ' . t('Annual Cost') . ' ' . t('must be greater than or equal to 0.'));
            }
        }

        if (isset($criteria['salary_min'])) {
            if (!is_numeric($criteria['salary_min'])) {
                return (t('Annual Salary') . ' ' . t('must be a number'));
            }
            if (intval($criteria['salary_min']) < 0) {
                return (t('Minimum') . ' ' . t('Annual Salary') . ' ' . t('must be greater than or equal to 0.'));
            }
        }
        if (isset($criteria['salary_max']) && $criteria['salary_max']) {
            if (!is_numeric($criteria['salary_max'])) {
                return (t('Annual Salary') . ' ' . t('must be a number'));
            }
            if (intval($criteria['salary_max']) < intval($criteria['salary_min'])) {
                return (t('Maximum') . ' ' . t('Annual Salary') . ' ' . t('must be greater than Minimum.'));
            }
            if (intval($criteria['salary_max']) < 0) {
                return (t('Maximum') . ' ' . t('Annual Salary') . ' ' . t('must be greater than or equal to 0.'));
            }
        }

        if (isset($criteria['benefits_min'])) {
            if (!is_numeric($criteria['benefits_min'])) {
                return (t('Annual Benefits') . ' ' . t('must be a number'));
            }
            if (intval($criteria['benefits_min']) < 0) {
                return (t('Minimum') . ' ' . t('Annual Benefits') . ' ' . t('must be greater than or equal to 0.'));
            }
        }
        if (isset($criteria['benefits_max']) && $criteria['benefits_max']) {
            if (!is_numeric($criteria['benefits_max'])) {
                return (t('Annual Benefits') . ' ' . t('must be a number'));
            }
            if (intval($criteria['benefits_max']) < intval($criteria['benefits_min'])) {
                return (t('Maximum') . ' ' . t('Annual Benefits') . ' ' . t('must be greater than Minimum.'));
            }
            if (intval($criteria['benefits_max']) < 0) {
                return (t('Maximum') . ' ' . t('Annual Benefits') . ' ' . t('must be greater than or equal to 0.'));
            }
        }

        if (isset($criteria['expenses_min'])) {
            if (!is_numeric($criteria['expenses_min'])) {
                return (t('Additional Expenses') . ' ' . t('must be a number'));
            }
            if (intval($criteria['expenses_min']) < 0) {
                return (t('Minimum') . ' ' . t('Additional Expenses') . ' ' . t('must be greater than or equal to 0.'));
            }
        }
        if (isset($criteria['expenses_max']) && $criteria['expenses_max']) {
            if (!is_numeric($criteria['expenses_max'])) {
                return (t('Additional Expenses') . ' ' . t('must be a number'));
            }
            if (intval($criteria['expenses_max']) < intval($criteria['expenses_min'])) {
                return (t('Maximum') . ' ' . t('Additional Expenses') . ' ' . t('must be greater than Minimum.'));
            }
            if (intval($criteria['expenses_max']) < 0) {
                return (t('Maximum') . ' ' . t('Additional Expenses') . ' ' . t('must be greater than or equal to 0.'));
            }
        }

        if (isset($criteria['stipend_min'])) {
            if (!is_numeric($criteria['stipend_min'])) {
                return (t('Annual Stipend') . ' ' . t('must be a number'));
            }
            if (intval($criteria['stipend_min']) < 0) {
                return (t('Minimum') . ' ' . t('Annual Stipend') . ' ' . t('must be greater than or equal to 0.'));
            }
        }
        if (isset($criteria['stipend_max']) && $criteria['stipend_max']) {
            if (!is_numeric($criteria['stipend_max'])) {
                return (t('Annual Stipend') . ' ' . t('must be a number'));
            }
            if (intval($criteria['stipend_max']) < intval($criteria['stipend_min'])) {
                return (t('Maximum') . ' ' . t('Annual Stipend') . ' ' . t('must be greater than Minimum.'));
            }
            if (intval($criteria['stipend_max']) < 0) {
                return (t('Maximum') . ' ' . t('Annual Stipend') . ' ' . t('must be greater than or equal to 0.'));
            }
        }

        return 1;
    }

    /**
     * creates a Zend_Db_Select object for querying employee module data and applies filters specified in form data
     *
     * @param $criteria - form input filter criteria
     * @return string|Zend_Db_Select
     */
    protected function employeeFilterQuery($criteria)
    {
        $joined = array();

        $isValid = $this->employeeValidateCriteria($criteria);
        if ($isValid !== 1) {
            return ($isValid);
        }

        $db = $this->dbfunc();

        $select = $db->select()
            ->from('employee', array());
        
        print_r($criteria);
        //TA:#419
        if (isset($criteria['show_is_active']) && $criteria['show_is_active']) {
           $select->columns("IF(employee.is_active = 1,'Active','Inactive') as active");
        }
        if(isset($criteria['is_active'])) {
            $select->where('is_active=' . $criteria['is_active']);
        }
        if (isset($criteria['show_employee_code']) && $criteria['show_employee_code']) {
        } 
        if(isset($criteria['employee_code'])) {
             $select->where('employee.id IN (?)', $criteria['employee_code']);
        }
        /////////

        if (isset($criteria['show_partner']) && $criteria['show_partner']) {
            if (!array_key_exists('partner', $joined)) {
                $select->joinLeft(array('partner'), 'partner.id = employee.partner_id', array());
                $joined['partner'] = 1;
            }
            $select->columns('partner.partner');
        }

        if (isset($criteria['partner']) && $criteria['partner']) {
            if (!array_key_exists('partner', $joined)) {
                $select->joinLeft(array('partner'), 'partner.id = employee.partner_id', array());
                $joined['partner'] = 1;
            }
            $select->where('partner.id = ?', $criteria['partner']);
        }

        // TODO: support n-tiers of regions.
        if (isset($criteria['showProvince']) && $criteria['showProvince']) {
            if (!array_key_exists('location', $joined)) {
                 //TA:#224 
                    $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                    $select->joinLeft('facility', 'link_employee_facility.facility_id = facility.id', array());
                    $select->joinLeft(array('location' => new Zend_Db_Expr('(' . Location::fluentSubquery() . ')')), 'location.id = facility.location_id', array());
                $joined['location'] = 1;
            }
            $select->columns('location.province_name');
        }

        // TODO: find a better way to filter finer-resolution locations? We shouldn't get all of the results in
        // TODO: province A if province A, district B and region c are selected, just what's in region c, but n-tier
        if (isset($criteria['province_id']) && count($criteria['province_id']) &&
            !((isset($criteria['district_id']) && count($criteria['district_id']))  ||
                (isset($criteria['region_c_id']) && count($criteria['region_c_id'])))) {
            $ids = $criteria['province_id'];
            if (!array_key_exists('location', $joined)) {
                 //TA:#224 
                    $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                    $select->joinLeft('facility', 'link_employee_facility.facility_id = facility.id', array());
                    $select->joinLeft(array('location' => new Zend_Db_Expr('(' . Location::fluentSubquery() . ')')), 'location.id = facility.location_id', array());
                $joined['location'] = 1;
            }
            if (is_array($ids)) {
                if (count($ids) > 1) {
                    $select->where('province_id IN (?)', $ids);
                } elseif (count($ids) === 1) {
                    $select->where('province_id = ?', $ids[0]);
                }
            }
            else {
                $select->where('province_id = ?', $ids);
            }
        }

        if (isset($criteria['showDistrict']) && $criteria['showDistrict']) {
            if (!array_key_exists('location', $joined)) {
                 //TA:#224 
                    $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                    $select->joinLeft('facility', 'link_employee_facility.facility_id = facility.id', array());
                    $select->joinLeft(array('location' => new Zend_Db_Expr('(' . Location::fluentSubquery() . ')')), 'location.id = facility.location_id', array());
                $joined['location'] = 1;
            }
            $select->columns('location.district_name');
        }

        if (isset($criteria['district_id']) && count($criteria['district_id']) &&
            !((isset($criteria['region_c_id']) && count($criteria['region_c_id'])))) {
            // This incoming data processing needs to happen because we're using renderFilter()
            if (is_array($criteria['district_id'])) {
                $ids = array_map(function ($item) {
                    $item = end(explode('_', $item));
                    return $item;
                }, $criteria['district_id']);
            }
            else {
                $ids = end(explode('_', $criteria['district_id']));
            }

            if (!array_key_exists('location', $joined)) {
                 //TA:#224 
                    $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                    $select->joinLeft('facility', 'link_employee_facility.facility_id = facility.id', array());
                    $select->joinLeft(array('location' => new Zend_Db_Expr('(' . Location::fluentSubquery() . ')')), 'location.id = facility.location_id', array());
                $joined['location'] = 1;
            }

            if (count($ids) > 1) {
                $select->where('district_id IN (?)', $ids);
            } elseif (count($ids) === 1) {
                $select->where('district_id = ?', $ids);
            }
        }

        if (isset($criteria['showRegionC']) && $criteria['showRegionC']) {
            if (!array_key_exists('location', $joined)) {
                 //TA:#224 
                    $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                    $select->joinLeft('facility', 'link_employee_facility.facility_id = facility.id', array());
                    $select->joinLeft(array('location' => new Zend_Db_Expr('(' . Location::fluentSubquery() . ')')), 'location.id = facility.location_id', array());
                $joined['location'] = 1;
            }
            $select->columns('location.region_c_name');
        }

        if (isset($criteria['region_c_id']) && count($criteria['region_c_id'])) {
            // This incoming data processing needs to happen because we're using renderFilter()

            if (is_array($criteria['region_c_id'])) {
                $ids = array_map(function ($item) {
                    $item = end(explode('_', $item));
                    return $item;
                }, $criteria['region_c_id']);
            }
            else {
                $ids = end(explode('_', $criteria['region_c_id']));
            }
            if (!array_key_exists('location', $joined)) {
                 //TA:#224 
                    $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                    $joined['link_employee_facility'] = 1;//TA:#419
                    $select->joinLeft('facility', 'link_employee_facility.facility_id = facility.id', array());
                    $select->joinLeft(array('location' => new Zend_Db_Expr('(' . Location::fluentSubquery() . ')')), 'location.id = facility.location_id', array());
                $joined['location'] = 1;
            }

            if (count($ids) > 1) {
                $select->where('region_c_id IN (?)', $ids);
            } elseif (count($ids) === 1) {
                $select->where('region_c_id = ?', $ids);
            }
        }
        
        //TA:#419
        if ((isset($criteria['show_dsd_model']) && $criteria['show_dsd_model']) || isset($criteria['dsd_model'])) {
            if (!array_key_exists('link_employee_facility', $joined)) {
                $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                $joined['link_employee_facility'] = 1;
            }
            if (!array_key_exists('employee_dsdmodel_option', $joined)) {
                $select->joinLeft('employee_dsdmodel_option', 'link_employee_facility.dsd_model_id = employee_dsdmodel_option.id', array());
                $joined['employee_dsdmodel_option'] = 1;
            }
            if(isset($criteria['show_dsd_model']) && $criteria['show_dsd_model']){
                $select->columns("employee_dsdmodel_option.employee_dsdmodel_phrase");
            }
            if(isset($criteria['dsd_model'])) {
                $select->where('link_employee_facility.dsd_model_id IN (?)', $criteria['dsd_model']);
            }
        }
        if ((isset($criteria['show_dsd_team']) && $criteria['show_dsd_team']) || isset($criteria['dsd_team'])) {
            if (!array_key_exists('link_employee_facility', $joined)) {
                $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                $joined['link_employee_facility'] = 1;
            }
            if (!array_key_exists('employee_dsdteam_option', $joined)) {
                $select->joinLeft('employee_dsdteam_option', 'link_employee_facility.dsd_team_id = employee_dsdteam_option.id', array());
                $joined['employee_dsdteam_option'] = 1;
            }
            if(isset($criteria['show_dsd_team']) && $criteria['show_dsd_team']){
                $select->columns("employee_dsdteam_option.employee_dsdteam_phrase");
            }
            if(isset($criteria['dsd_team'])) {
                $select->where('link_employee_facility.dsd_team_id IN (?)', $criteria['dsd_team']);
            }
        }
        if ((isset($criteria['show_fte_min']) && $criteria['show_fte_min']) || $criteria['fte_min'] || $criteria['fte_max']) {
            if (!array_key_exists('link_employee_facility', $joined)) {
                $select->joinLeft('link_employee_facility', 'link_employee_facility.employee_id = employee.id', array());
                $joined['link_employee_facility'] = 1;
            }
            if(isset($criteria['show_fte_min']) && $criteria['show_fte_min']){
                $select->columns("link_employee_facility.hiv_fte_related");
            }
            if($criteria['fte_min']){
                $select->where("link_employee_facility.hiv_fte_related > " . $criteria['fte_min']);
            }
            if($criteria['fte_max']){
                $select->where("link_employee_facility.hiv_fte_related < " . $criteria['fte_max']);
            }
        }
        if ((isset($criteria['show_contract_start_date_from']) && $criteria['show_contract_start_date_from']) || $criteria['contract_start_date_from'] || $criteria['contract_start_date_to']) {
            if(isset($criteria['show_contract_start_date_from']) && $criteria['show_contract_start_date_from']){
                $select->columns("SUBSTRING_INDEX(employee.agreement_start_date, ' ', 1) as contract_start_date");
            }
            if($criteria['contract_start_date_from']){
                $select->where("employee.agreement_start_date > '" . $criteria['contract_start_date_from'] . "'");
            }
            if($criteria['contract_start_date_to']){
                $select->where("employee.agreement_start_date < '" . $criteria['contract_start_date_to']. "'");
            }     
        }
        if ((isset($criteria['show_contract_end_date_from']) && $criteria['show_contract_end_date_from']) || $criteria['contract_end_date_from'] || $criteria['contract_end_date_to']) {
            if(isset($criteria['show_contract_end_date_from']) && $criteria['show_contract_end_date_from']){
                $select->columns("SUBSTRING_INDEX(employee.agreement_end_date, ' ', 1) as contract_end_date");
            }
            if($criteria['contract_end_date_from']){
                $select->where("employee.agreement_end_date > '" . $criteria['contract_end_date_from']. "'");
            }
            if($criteria['contract_end_date_to']){
                $select->where("employee.agreement_end_date < '" . $criteria['contract_end_date_to']. "'");
            }
        }
        ////

        // based at
        if (isset($criteria['show_based_at']) && $criteria['show_based_at']) {
            if (!array_key_exists('employee_base_option', $joined)) {
                $select->joinLeft('employee_base_option', 'employee_base_option.id = employee.employee_base_option_id', array());
                $joined['employee_base_option'] = 1;
            }
            $select->columns('employee_base_option.base_phrase');

            if (isset($criteria['based_at'])) {
                // this functionality relies on the translation for the string 'Other' to be one of the entries in the employee_base_option table in the base_phrase column
                $basedAtOtherId = $db->fetchOne($db->select()->from('employee_base_option', array('id'))->where('base_phrase = "' . t('Other') . '"'));
                if (($criteria['based_at'] == 0) || ($basedAtOtherId == $criteria['based_at'])) {
                    $select->columns('employee.based_at_other');
                }
            }
        }

        if (isset($criteria['based_at']) && $criteria['based_at']) {
            if (!array_key_exists('employee_base_option', $joined)) {
                $select->joinLeft('employee_base_option', 'employee_base_option.id = employee.employee_base_option_id', array());
                $joined['employee_base_option'] = 1;
            }
            $select->where('employee_base_option.id = ?', $criteria['based_at']);
        }

        // site
        if (isset($criteria['show_site']) && $criteria['show_site']) {
            if (!array_key_exists('facility', $joined)) {
                $select->joinLeft('facility', 'facility.id = employee.site_id', array());
                $joined['facility'] = 1;
            }
            $select->columns('facility.facility_name');
        }else if (isset($criteria['show_facilityInput']) && $criteria['show_facilityInput']) {//TA:#293.1
            if (!array_key_exists('facility', $joined)) {
                $select->joinLeft('facility', 'facility.id = employee.site_id', array());
                $joined['facility'] = 1;
            }
            $select->columns('facility.facility_name');
        }
      
        if (isset($criteria['site']) && $criteria['site']) {
            if (!array_key_exists('facility', $joined)) {
                $select->joinLeft('facility', 'facility.id = employee.site_id', array());
                $joined['facility'] = 1;
            }
            $select->where('facility.id = ?', $criteria['site']);
        }else if (isset($criteria['facilityInput']) && $criteria['facilityInput']) {//TA:#293.1
            if (!array_key_exists('facility', $joined)) {
                $select->joinLeft('facility', 'facility.id = employee.site_id', array());
                $joined['facility'] = 1;
            }
            $select->where('facility.id = ?', $criteria['facilityInput']);
        }

        // facility type
        if (isset($criteria['show_facility_type']) && $criteria['show_facility_type']) {
            if (!array_key_exists('facility_type_option', $joined)) {
                $select->joinLeft('facility_type_option', 'facility_type_option.id = facility.type_option_id', array());//TA:#386
                $joined['facility_type_option'] = 1;
            }
            $select->columns('facility_type_option.facility_type_phrase');
        }
        if (isset($criteria['facility_type']) && $criteria['facility_type']) {
            $select->where('facility.type_option_id = ?', $criteria['facility_type']); //TA:#386
        }

        // classification
        if (isset($criteria['show_classification']) && $criteria['show_classification']) {
            if (!array_key_exists('employee_qualification_option', $joined)) {
                $select->joinLeft('employee_qualification_option', 'employee_qualification_option.id = employee.employee_qualification_option_id', array());
                $joined['employee_qualification_option'] = 1;
            }
            $select->columns('employee_qualification_option.qualification_phrase');
        }
        if (isset($criteria['classification']) && $criteria['classification']) {
            if (!array_key_exists('employee_qualification_option', $joined)) {
                $select->joinLeft('employee_qualification_option', 'employee_qualification_option.id = employee.employee_qualification_option_id', array());
                $joined['employee_qualification_option'] = 1;
            }
            $select->where('employee_qualification_option_id = ?', $criteria['classification']);
        }

        // funded hours per week
        // labelTwoFields uses the name of the first field for the 'show' checkbox
        if (isset($criteria['show_hours_min']) && $criteria['show_hours_min']) {
            $select->columns('funded_hours_per_week');
        }
        if (isset($criteria['hours_min']) && intval($criteria['hours_min']) >= 0) {
            $select->where('funded_hours_per_week >= ?', intval($criteria['hours_min']));
        }
        if (isset($criteria['hours_max']) && $criteria['hours_max']) {
            $select->where('funded_hours_per_week <= ?', intval($criteria['hours_max']));
        }

        // cost
        // labelTwoFields uses the name of the first field for the 'show' checkbox
        if (isset($criteria['show_cost_min']) && $criteria['show_cost_min']) {
            $select->columns('annual_cost');
        }
        if (isset($criteria['cost_min']) && $criteria['cost_min'] >= 0) {
            $select->where('annual_cost >= ?', intval($criteria['cost_min']));
        }
        if (isset($criteria['cost_max']) && $criteria['cost_max']) {
            $select->where('annual_cost <= ?', intval($criteria['cost_max']));
        }

        // role
        if (isset($criteria['show_primary_role']) && $criteria['show_primary_role']) {
            if (!array_key_exists('employee_role_option', $joined)) {
                $select->joinLeft('employee_role_option', 'employee_role_option.id = employee.employee_role_option_id', array());
                $joined['employee_role_option'] = 1;
            }
            $select->columns('employee_role_option.role_phrase');
        }
        if (isset($criteria['primary_role']) && $criteria['primary_role']) {
            if (!array_key_exists('employee_role_option', $joined)) {
                $select->joinLeft('employee_role_option', 'employee_role_option.id = employee.employee_role_option_id', array());
                $joined['employee_role_option'] = 1;
            }
            $select->where('employee_role_option.id = ?', $criteria['primary_role']);
        }

        // transition (used with transition_type)
        if (isset($criteria['transition']) && $criteria['transition']) {

            if (isset($criteria['transition_type']) && $criteria['transition_type']) {
                if ($criteria['transition_type'] == '1') {
                    // Actual Transition
                    $select->where('employee.employee_transition_complete_option_id = ?', $criteria['transition']);
                }
                elseif ($criteria['transition_type'] == '2') {
                    // Intended Transition
                    $select->where('employee.employee_transition_option_id = ?', $criteria['transition']);
                }
            }
            else {
                $select->where('employee.employee_transition_option_id = ? OR employee.employ_transition_complete_option_id = ?',
                    $criteria['transition'], $criteria['transition']);
            }
        }

        // intended transition
        if (isset($criteria['show_intended_transition']) && $criteria['show_intended_transition']) {
            if (!array_key_exists('intended_employee_transition_option', $joined)) {
                $select->joinLeft(array('intended_employee_transition_option' => 'employee_transition_option'), 'intended_employee_transition_option.id = employee.employee_transition_option_id', array());
                $joined['intended_employee_transition_option'] = 1;
            }
            $select->columns('intended_employee_transition_option.transition_phrase AS intended_transition');
        }
        if (isset($criteria['intended_transition']) && $criteria['intended_transition']) {
            if (!array_key_exists('intended_employee_transition_option', $joined)) {
                $select->joinLeft(array('intended_employee_transition_option' => 'employee_transition_option'), 'intended_employee_transition_option.id = employee.employee_transition_option_id', array());
                $joined['intended_employee_transition_option'] = 1;
            }
            $select->where('intended_employee_transition_option.id = ?', $criteria['intended_transition']);
        }

        // transition outcome
        if (isset($criteria['show_actual_transition']) && $criteria['show_actual_transition']) {
            if (!array_key_exists('actual_employee_transition_option', $joined)) {
                $select->joinLeft(array('actual_employee_transition_option' => 'employee_transition_option'),
                    'actual_employee_transition_option.id = employee.employee_transition_complete_option_id', array());
                $joined['actual_employee_transition_option'] = 1;
            }
            $select->columns('actual_employee_transition_option.transition_phrase AS actual_transition');

        }
        if (isset($criteria['actual_transition']) && $criteria['actual_transition']) {
            if (!array_key_exists('actual_employee_transition_option', $joined)) {
                $select->joinLeft(array('actual_employee_transition_option' => 'employee_transition_option'),
                    'actual_employee_transition_option.id = employee.employee_transition_complete_option_id', array());
                $joined['actual_employee_transition_option'] = 1;
            }
            $select->where('actual_employee_transition_option.id = ?', $criteria['actual_transition']);

        }

        // transition date
        // labelTwoFields uses the name of the first field for the 'show' checkbox
        if (isset($criteria['show_transition_start_date']) && $criteria['show_transition_start_date']) {
            $select->columns(array('transition_complete_date' => new Zend_Db_Expr("DATE_FORMAT(transition_complete_date, '%d/%m/%Y')")));
        }
        if (isset($criteria['transition_start_date']) && $criteria['transition_start_date']) {
            $d = DateTime::createFromFormat('d/m/Y', $criteria['transition_start_date']);
            $select->where('transition_complete_date >= ?', $d->format('Y-m-d'));
        }
        if (isset($criteria['transition_end_date']) && $criteria['transition_end_date']) {
            $d = DateTime::createFromFormat('d/m/Y', $criteria['transition_end_date']);
            $select->where('transition_complete_date <= ?', $d->format('Y-m-d'));
        }

        // salary
        // labelTwoFields uses the name of the first field for the 'show' checkbox
        if (isset($criteria['show_salary_min']) && $criteria['show_salary_min']) {
            $select->columns('salary');
        }
        if (isset($criteria['salary_min']) && intval($criteria['salary_min']) >= 0) {
            $select->where('salary >= ?', intval($criteria['salary_min']));
        }
        if (isset($criteria['salary_max']) && $criteria['salary_max']) {
            $select->where('salary <= ?', intval($criteria['salary_max']));
        }

        // benefits
        // labelTwoFields uses the name of the first field for the 'show' checkbox
        if (isset($criteria['show_benefits_min']) && $criteria['show_benefits_min']) {
            $select->columns('benefits');
        }
        if (isset($criteria['benefits_min']) && intval($criteria['benefits_min']) >= 0) {
            $select->where('benefits >= ?', intval($criteria['benefits_min']));
        }
        if (isset($criteria['benefits_max']) && $criteria['benefits_max']) {
            $select->where('benefits <= ?', intval($criteria['benefits_max']));
        }

        // expenses
        // labelTwoFields uses the name of the first field for the 'show' checkbox
        if (isset($criteria['show_expenses_min']) && $criteria['show_expenses_min']) {
            $select->columns('additional_expenses');
        }
        if (isset($criteria['expenses_min']) && $criteria['expenses_min'] >= 0) {
            $select->where('additional_expenses >= ?', intval($criteria['expenses_min']));
        }

        if (isset($criteria['expenses_max']) && $criteria['expenses_max']) {
            $select->where('additional_expenses <= ?', intval($criteria['expenses_max']));
        }

        // stipend
        // labelTwoFields uses the name of the first field for the 'show' checkbox
        if (isset($criteria['show_stipend_min']) && $criteria['show_stipend_min']) {
            $select->columns('stipend');
        }
        if (isset($criteria['stipend_min']) && $criteria['stipend_min'] >= 0) {
            $select->where('stipend >= ?', intval($criteria['stipend_min']));
        }
        if (isset($criteria['stipend_max']) && $criteria['stipend_max']) {
            $select->where('stipend <= ?', intval($criteria['stipend_max']));
        }

        if (isset($criteria['show_period_start_date']) && $criteria['show_period_start_date']) {

        }
        if (isset($criteria['period_start_date']) && $criteria['period_start_date']) {

        }
        if (isset($criteria['period_end_date']) && $criteria['period_end_date']) {

        }

        if (isset($criteria['show_mechanism']) && $criteria['show_mechanism']) {
            if (!array_key_exists('link_mechanism_employee', $joined)) {
                $select->joinLeft('link_mechanism_employee', 'link_mechanism_employee.employee_id = employee.id', array());
                $joined['link_mechanism_employee'] = 1;
            }
            if (!array_key_exists('mechanism_option', $joined)) {
                $select->joinLeft('mechanism_option', 'mechanism_option.id = link_mechanism_employee.mechanism_option_id', array());
                $joined['mechanism_option'] = 1;
            }
            $select->columns('mechanism_phrase');

        }
        if (isset($criteria['mechanism']) && $criteria['mechanism']) {
            if (!array_key_exists('link_mechanism_employee', $joined)) {
                $select->joinLeft('link_mechanism_employee', 'link_mechanism_employee.employee_id = employee.id', array());
                $joined['link_mechanism_employee'] = 1;
            }
            if (!array_key_exists('mechanism_option', $joined)) {
                $select->joinLeft('mechanism_option', 'mechanism_option.id = link_mechanism_employee.mechanism_option_id', array());
                $joined['mechanism_option'] = 1;
            }
            if (count($criteria['mechanism']) > 1) {
                $select->where('mechanism_option.id in (?)', $criteria['mechanism']);
            } elseif (count($criteria['mechanism']) == 1) {
                $select->where('mechanism_option.id = ?', $criteria['mechanism']);
            }
        }

        if (isset($criteria['show_funder']) && $criteria['show_funder']) {
            if (!array_key_exists('link_mechanism_employee', $joined)) {
                $select->joinLeft('link_mechanism_employee', 'link_mechanism_employee.employee_id = employee.id', array());
                $joined['link_mechanism_employee'] = 1;
            }
            if (!array_key_exists('mechanism_option', $joined)) {
                $select->joinLeft('mechanism_option', 'mechanism_option.id = link_mechanism_employee.mechanism_option_id', array());
                $joined['mechanism_option'] = 1;
            }
            if (!array_key_exists('partner_funder_option', $joined)) {
                $select->joinLeft('partner_funder_option', 'partner_funder_option.id = mechanism_option.funder_id', array());
                $joined['partner_funder_option'] = 1;
            }

            $select->columns('funder_phrase');
        }
        if (isset($criteria['funder']) && ($criteria['funder'])) {
            if (!array_key_exists('link_mechanism_employee', $joined)) {
                $select->joinLeft('link_mechanism_employee', 'link_mechanism_employee.employee_id = employee.id', array());
                $joined['link_mechanism_employee'] = 1;
            }
            if (!array_key_exists('mechanism_option', $joined)) {
                $select->joinLeft('mechanism_option', 'mechanism_option.id = link_mechanism_employee.mechanism_option_id', array());
                $joined['mechanism_option'] = 1;
            }
            if (!array_key_exists('partner_funder_option', $joined)) {
                $select->joinLeft('partner_funder_option', 'partner_funder_option.id = mechanism_option.funder_id', array());
                $joined['partner_funder_option'] = 1;
            }

            if (count($criteria['funder']) > 1) {
                $select->where('partner_funder_option.id in (?)', $criteria['funder']);
            } elseif (count($criteria['funder']) == 1) {
                $select->where('partner_funder_option.id = ?', $criteria['funder']);
            }
        }

        // labelTwoFields uses the name of the first field for the 'show' checkbox
        if (isset($criteria['show_contract_start_date']) && $criteria['show_contract_start_date']) {
            $select->columns(array('employee.agreement_end_date' => new Zend_Db_Expr("DATE_FORMAT(employee.agreement_end_date, '%d/%m/%Y')")));

        }
        if (isset($criteria['contract_start_date']) && $criteria['contract_start_date'] >= 0) {
            $d = DateTime::createFromFormat('d/m/Y', $criteria['contract_start_date']);
            $select->where('employee.agreement_end_date >= ?', $d->format('Y-m-d'));
        }

        if (isset($criteria['contract_end_date']) && $criteria['contract_end_date']) {
            $d = DateTime::createFromFormat('d/m/Y', $criteria['contract_end_date']);
            $select->where('employee.agreement_end_date <= ?', $d->format('Y-m-d'));
        }

        if (!$this->hasACL('training_organizer_option_all')) {
            // limit results to only mechanisms owned by partners and subpartners that the user account can access
            $uid = $this->isLoggedIn();

            if (!array_key_exists('partner', $joined)) {
                $select->joinLeft(array('partner'), 'partner.id = employee.partner_id', array());
                $joined['partner'] = 1;
            }

            if (!array_key_exists('link_mechanism_partner', $joined)) {
                $select->joinLeft(array('link_mechanism_partner'), 'partner.id = link_mechanism_partner.partner_id', array());
                $joined['link_mechanism_partner'] = 1;
            }

            //TA:#415 make visible results by user mechanism accessebility, it will be added by next code
//             if (!array_key_exists('user_to_organizer_access', $joined)) {
//                 $select->joinLeft(array('user_to_organizer_access'),
//                     'user_to_organizer_access.training_organizer_option_id = partner.organizer_option_id', array());
//                 $joined['user_to_organizer_access'] = 1;
//             }
//             $select->where('user_to_organizer_access.user_id = ?', $uid);           
            ////
        }

        $s = $select->__toString();
        print $s;
        return $select;
    }
}
