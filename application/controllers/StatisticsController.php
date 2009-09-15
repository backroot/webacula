<?php
/**
 * Copyright 2007, 2008 Yuri Timofeev tim4dev@gmail.com
 *
 * This file is part of Webacula.
 *
 * Webacula is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Webacula is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Webacula.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @author Yuri Timofeev <tim4dev@gmail.com>
 * @package webacula
 * @license http://www.gnu.org/licenses/gpl-3.0.html GNU Public License
 *
 * $Id: StatisticsController.php 359 2009-07-01 20:28:31Z tim4dev $
 */
/* Zend_Controller_Action */
require_once 'Zend/Controller/Action.php';

class StatisticsController extends Zend_Controller_Action
{

	function init()
	{
		$this->view->baseUrl = $this->_request->getBaseUrl();
		$this->view->translate = Zend_Registry::get('translate');
	}

    function listjobtotalsAction()
    {
    	$this->view->title = $this->view->translate->_("List of Job Totals");

        // get status dir
        $config = Zend_Registry::get('config');

    	if ( !file_exists($config->bacula->bconsole))	{
            $this->view->result_error = 'NOFOUND_BCONSOLE';
            $this->render();
            return;
        }

        $bconsolecmd = '';
        if ( isset($config->bacula->sudo))	{
            // run with sudo
            $bconsolecmd = $config->bacula->sudo . ' ' . $config->bacula->bconsole . ' ' . $config->bacula->bconsolecmd;
        } else {
            $bconsolecmd = $config->bacula->bconsole . ' ' . $config->bacula->bconsolecmd;
        }

        exec($bconsolecmd . " <<EOF
list jobtotals
@quit
EOF",
$command_output, $return_var);

            //echo "<pre>command_output:<br>" . print_r($command_output) . "<br><br>return_var = " . $return_var . "</pre>"; exit;

            $this->view->command_output = $command_output;

            // check return status of the executed command
            if ( $return_var != 0 )	{
                $this->view->result_error = 'ERROR_BCONSOLE';
            }
    	    return;
    }
}