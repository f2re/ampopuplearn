<?php
/**
 * POST helper functions for get pop music types
 *
 * @package    Threat_Scholar
 * @subpackage Threat_Scholar/public
 * @author     Steve Giorgi <steve@dotekimedia.com>
 */
namespace AmpopupLearn\Helpers;

class PostHelper {

    /**
     * Get music categories as tabs
     * @is_shortly - if true, then translate short titles 
     */
    public static function get_music_categories($is_shortly=false){
        
        $study_units = new \WP_query(array(
                'post_type'			=>	'sfwd-courses',
                'orderby'			=>	'',
                'posts_per_page'	=>	-1
            )
        );

        $study_units_data = array();

        foreach($study_units->posts as $study_unit) {

            $suid     = $study_unit->ID;
            $user_id  = get_current_user_id();
            $progress = learndash_course_progress(array("user_id" => $user_id, "course_id" => $suid, "array" => true));
            $status   = ($progress["percentage"] >= 1)? "Resume Course":"Take Course";
            $content  = wp_strip_all_tags($study_unit->post_content);
            $title    = $study_unit->post_title;
            if ( $is_shortly ){
                $_title = explode(' ',$title);
                if ( count($_title)>3 ){
                    unset($_title[2]);
                    $title = join(' ',$_title);
                }                
            }
            $study_units_data[] = array(
                'id'        =>  $suid,
                'title'		=>	$title,
                'excerpt'	=>	$content,
                'url'       =>	esc_url(get_permalink($suid)),
                'progress'	=>	$progress,
                'status'	=>	$status,
            );
        }

        return $study_units_data;
    }

    // get category students
    public static function get_category_students($category_id=123){
        $blogusers = get_users();
        $result = [];
        $i = 0;
		foreach($blogusers as $user){
			$fullname = get_user_meta($user->ID,'first_name',true).' '.get_user_meta($user->ID,'last_name',true);
			$usermail = $user->user_email;
			$courseaccesscounter =  get_user_meta($user->ID,'_learndash_memberpress_enrolled_courses_access_counter',true);
            $courseprogress =  get_user_meta($user->ID, '_sfwd-course_progress');
            if ( is_array($courseprogress) && isset($courseprogress[0]) ){
                $_keys = [];
                foreach ( $courseprogress[0] as $key=>$arr ){
                    $_keys[] = $key;
                }
                $courseprogress = $_keys;
            }
			$metadata = get_user_meta($user->ID);
			$user->metadata = $metadata;
            $result[$i]['name'] = $fullname;
            $result[$i]['avatar'] = 'https://via.placeholder.com/30';
            $result[$i]['points'] = rand(6000,10000);
            $result[$i]['percents'] = rand(0,100);
            $result[$i]['startDate'] = '01-15-2019';
            $result[$i]['loginCount'] = rand(0,100);
            $result[$i]['courseTime'] = rand(1,24).':'.rand(1,60).':00';
            $result[$i]['lastStepUnit'] = 'Jess Study Unit';
            $result[$i]['lastStepChapter'] = 'Jess Study Unit';
            $result[$i]['lastLoginLdate'] = '01-15-2020';
            $result[$i]['email'] = $usermail;
            $result[$i]['active'] = false;
            $result[$i]['quiz'] = [
                                    [
                                        'name' => 'Chapter 1 Quiz',
                                        'attempts'=>rand(0,50),
                                        'score'=>rand(0,100),
                                        'date' => '01-15-2019',
                                        'duration' => rand(1,24).':'.rand(1,60).':00',
                                    ]];
            $result[$i]['courses'] = [
                [
                    'name' => 'Chapter 1 Quiz',
                    'attempts'=>rand(0,50),
                    'score'=>rand(0,100),
                    'date' => '01-15-2019',
                    'duration' => rand(1,24).':'.rand(1,60).':00',
                ]];

			// $result[$user->ID]['usermail'] = $usermail;
			// $result[$user->ID]['courseaccesscounter'] = $courseaccesscounter;
            $result[$i]['courseprogress'] = $courseprogress;
            $i++;
        }
        // echo '<pre>';
        // print_r($result);
        // echo '</pre>';die;
        
        // $result = [
        //     [ 'name'=> 'Steve G.',
        //       'avatar'=> 'https://via.placeholder.com/30',
        //       'points' => rand(6000,10000),
        //       'percents' => rand(0,100),
        //       'startDate' => '01-15-2019',
        //       'loginCount' => rand(0,100),
        //       'courseTime' => rand(1,24).':'.rand(1,60).':00',
        //       'lastStepUnit' => 'Jess Study Unit',
        //       'lastStepChapter' => 'Chapter 7: Big Bang Jazz Swi',
        //       'lastLoginLdate' => '01-15-2020',
        //       'email' => 'asd@sdfsdf.ru',
        //       'active'=>false,
        //       'quiz' => [
        //         [
        //             'name' => 'Chapter 1 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 2 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 3 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ]
        //       ],
        //       'courses'=>[
        //         [ 'name'=> 'Course 1',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 2',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 3',
        //            'score'=> rand(0,100) ],
        //       ]
        //     ],
		// 	[ 'name'=> 'Anna K.',
        //       'avatar'=> 'https://via.placeholder.com/30',
        //       'points' => rand(6000,10000),
        //       'percents' => rand(0,100),
        //       'startDate' => '01-15-2019',
        //       'loginCount' => rand(0,100),
        //       'courseTime' => rand(1,24).':'.rand(1,60).':00',
        //       'lastStepUnit' => 'Jess Study Unit',
        //       'lastStepChapter' => 'Chapter 7: Big Bang Jazz Swi',
        //       'lastLoginLdate' => '01-15-2020',
        //       'email' => 'asd@sdfsdf.ru',
        //       'active'=>false,
        //       'quiz' => [
        //         [
        //             'name' => 'Chapter 1 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 2 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 3 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ]
        //       ],
        //       'courses'=>[
        //         [ 'name'=> 'Course 1',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 2',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 3',
        //            'score'=> rand(0,100) ],
        //       ]
        //     ],
		// 	[ 'name'=> 'Samantha E.',
        //       'avatar'=> 'https://via.placeholder.com/30',
        //       'points' => rand(6000,10000),
        //       'percents' => rand(0,100),
        //       'startDate' => '01-15-2019',
        //       'loginCount' => rand(0,100),
        //       'courseTime' => rand(1,24).':'.rand(1,60).':00',
        //       'lastStepUnit' => 'Jess Study Unit',
        //       'lastStepChapter' => 'Chapter 7: Big Bang Jazz Swi',
        //       'lastLoginLdate' => '01-15-2020',
        //       'email' => 'asd@sdfsdf.ru',
        //       'active'=>false,
        //       'quiz' => [
        //         [
        //             'name' => 'Chapter 1 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 2 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 3 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ]
        //       ],
        //       'courses'=>[
        //         [ 'name'=> 'Course 1',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 2',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 3',
        //            'score'=> rand(0,100) ],
        //       ]
        //     ],
		// 	[ 'name'=> 'John P.',
        //       'avatar'=> 'https://via.placeholder.com/30',
        //       'points' => rand(6000,10000),
        //       'percents' => rand(0,100),
        //       'startDate' => '01-15-2019',
        //       'loginCount' => rand(0,100),
        //       'courseTime' => rand(1,24).':'.rand(1,60).':00',
        //       'lastStepUnit' => 'Jess Study Unit',
        //       'lastStepChapter' => 'Chapter 7: Big Bang Jazz Swi',
        //       'lastLoginLdate' => '01-15-2020',
        //       'email' => 'asd@sdfsdf.ru',
        //       'active'=>false,
        //       'quiz' => [
        //         [
        //             'name' => 'Chapter 1 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 2 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 3 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ]
        //       ],
        //       'courses'=>[
        //         [ 'name'=> 'Course 1',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 2',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 3',
        //            'score'=> rand(0,100) ],
        //       ]
        //     ],
		// 	[ 'name'=> 'John P.',
        //       'avatar'=> 'https://via.placeholder.com/30',
        //       'points' => rand(6000,10000),
        //       'percents' => rand(0,100),
        //       'startDate' => '01-15-2019',
        //       'loginCount' => rand(0,100),
        //       'courseTime' => rand(1,24).':'.rand(1,60).':00',
        //       'lastStepUnit' => 'Jess Study Unit',
        //       'lastStepChapter' => 'Chapter 7: Big Bang Jazz Swi',
        //       'lastLoginLdate' => '01-15-2020',
        //       'email' => 'asd@sdfsdf.ru',
        //       'active'=>false,
        //       'quiz' => [
        //         [
        //             'name' => 'Chapter 1 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 2 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ],
        //         [
        //             'name' => 'Chapter 3 Quiz',
        //             'attempts'=>rand(0,50),
        //             'score'=>rand(0,100),
        //             'date' => '01-15-2019',
        //             'duration' => rand(1,24).':'.rand(1,60).':00',
        //         ]
        //       ],
        //       'courses'=>[
        //         [ 'name'=> 'Course 1',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 2',
        //            'score'=> rand(0,100) ],
        //            [ 'name'=> 'Course 3',
        //            'score'=> rand(0,100) ],
        //       ]
        //     ]
        // ];
        
        return $result;
    }

    public static function get_user_report_data(){
        if ( class_exists( 'Learndash_Admin_Settings_Data_Reports' ) ) {
            $ld_admin_settings_data_reports = new Learndash_Admin_Settings_Data_Reports;
            $reply_data['data'] = $ld_admin_settings_data_reports->do_data_reports( $report_post_args, $reply_data );
            unset( $reply_data['data']['filters'] );
            
            $output = $reply_data;
        }
        return $output;
    }

    /**
     * get report of dashboard data
     *
     * @return void
     */
    public static function get_report(){
        $transient_data = array();

        $data=[];

        if ( ( isset( $data['filters'] ) ) && ( !empty( $data['filters'] ) ) ) {
            $transient_data = $query = \wp_parse_args( $transient_data, $data['filters'] );                                        
        } else {
        
            $transient_data['activity_status'] =	array('NOT_STARTED' , 'IN_PROGRESS', 'COMPLETED');
            
            if ( ( isset( $data['group_id'] ) ) && ( !empty( $data['group_id'] ) ) ) {
                $transient_data['users_ids'] = \learndash_get_groups_user_ids( intval( $data['group_id'] ) );
                $transient_data['posts_ids'] = \learndash_group_enrolled_courses( intval( $data['group_id'] ) );
            } else {
                $transient_data['posts_ids'] = '';
                $transient_data['users_ids'] = \learndash_get_report_user_ids();
            }
            $transient_data['activity_status'] = array('NOT_STARTED', 'IN_PROGRESS', 'COMPLETED');
        }

        $data['report_download_link'] = $transient_data['report_url'];
        $data['total_count']          = $transient_data['total_users'];
        $data['transient_data']       = $transient_data;
        $data['get_current_user_id']  = \get_current_user_id();
        
        $transient_data['total_users'] = count( $transient_data['users_ids'] );
        
        	
        if ( !empty( $transient_data['users_ids'] ) ) {
                                
            // If we are doing the initial 'init' then we return so we can show the progress meter.			
            if ( $_DOING_INIT != true) {
            
                $course_query_args = array(
                    'orderby'		=>	'title',
                    'order'			=>	'ASC',
                    'nopaging'		=>	true
                );

                $activity_query_args = array(
                    'post_types' 		=> 	'sfwd-courses',
                    'activity_types'	=>	'course',
                    'activity_status'	=>	$transient_data['activity_status'],
                    'orderby_order'		=>	'users.ID, posts.post_title',
                    'date_format'		=>	'F j, Y H:i:s',
                    'per_page'			=>	''
                );
                
                $course_progress_data = array();
                
                foreach( $transient_data['users_ids'] as $user_id_idx => $user_id ) {
            
                    unset( $transient_data['users_ids'][$user_id_idx] );
                
                    $report_user = \get_user_by('id', $user_id);
                    if ( $report_user !== false ) {
                        if ( ( isset( $transient_data['course_ids'] ) ) && ( !empty( $transient_data['course_ids'] ) ) ) {
                            $post_ids = $transient_data['course_ids'];
                            
                        } else if ( ( isset( $transient_data['posts_ids'] ) ) && ( !empty( $transient_data['posts_ids'] ) ) ) {
                            $post_ids = $transient_data['posts_ids'];

                        } else {
                            $post_ids = \learndash_user_get_enrolled_courses( intval( $user_id ), $course_query_args, true );
                        }

                        if ( !empty( $post_ids ) ) {

                            $activity_query_args['user_ids'] = array( $user_id );
                            $activity_query_args['post_ids'] = $post_ids;
                        
                            $user_courses_reports = \learndash_reports_get_activity( $activity_query_args );
                            if ( !empty( $user_courses_reports['results'] ) ) {
                                foreach( $user_courses_reports['results'] as $result ) {											
                                    $row = array();
                                
                                    foreach( $this->data_headers as $header_key => $header_data ) {
                                    
                                        if ( ( isset( $header_data['display'] ) ) && ( !empty( $header_data['display'] ) ) ) {
                                            $row[$header_key] = \call_user_func_array( $header_data['display'], array(
                                                    'header_value'	=>	$header_data['default'],
                                                    'header_key'	=>	$header_key, 
                                                    'item' 			=> 	$result, 
                                                    'report_user' 	=> 	$report_user,
                                                ) 
                                            );
                                        } else if ( ( isset( $header_data['default'] ) ) && ( !empty( $header_data['default'] ) ) ) {
                                            $row[$header_key] = $header_data['default'];
                                        } else {
                                            $row[$header_key] = '';
                                        }
                                    }

                                    if ( !empty($row ) ) {
                                        $course_progress_data[] = $row;
                                    }
                                }
                            } else {
                                
                                if ( ( is_array( $transient_data['activity_status'] ) ) 
                                    && ( count( $transient_data['activity_status'] ) > 1 ) 
                                    && ( in_array('NOT_STARTED', $transient_data['activity_status'] ) ) ) {
                                    
                                    $row = array();
                            
                                    foreach( $this->data_headers as $header_key => $header_data ) {
                                
                                        if ( ( isset( $header_data['display'] ) ) && ( !empty( $header_data['display'] ) ) ) {
                                            $row[$header_key] = \call_user_func_array( $header_data['display'], array(
                                                    'header_value'	=>	$header_data['default'],
                                                    'header_key'	=>	$header_key,
                                                    'item' 			=> 	new stdClass(), 
                                                    'report_user' 	=> 	$report_user,
                                                ) 
                                            );
                                        } else if ( ( isset( $header_data['default'] ) ) && ( !empty( $header_data['default'] ) ) ) {
                                            $row[$header_key] = $header_data['default'];
                                        } else {
                                            $row[$header_key] = '';
                                        }
                                    }

                                    if ( !empty($row ) ) {
                                        $course_progress_data[] = $row;
                                    }
                                }
                            }
                        }
                    }
                       
                }

                if ( !empty( $course_progress_data ) ) {
                    $data['progress'] = $course_progress_data;
                }
            } 
            
            $data['result_count'] 		= 	$data['total_count'] - count( $transient_data['users_ids'] );
            $data['progress_percent'] 	= 	( $data['result_count'] / $data['total_count'] ) * 100;
            // $data['progress_label']		= 	sprintf( esc_html_x('%1$d of %2$s Users', 'placeholders: result count, total count', 'learndash'), $data['result_count'], $data['total_count']);
        }

        $response = \rest_ensure_response( $data ); 
        return $response;
    }
}