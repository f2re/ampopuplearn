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
                'title'		=>	$title,
                'excerpt'	=>	$content,
                'url'       =>	esc_url(get_permalink($suid)),
                'progress'	=>	$progress,
                'status'	=>	$status,
            );
        }

        return $study_units_data;
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