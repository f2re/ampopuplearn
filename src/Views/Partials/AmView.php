<?php
/**
 * Certifications is the user added certifications
 * 
 * Certifications are what the user adds when they first start, this is a custom post type, 
 * they need to track their certification. When they finish the trainings they earn a certificate, 
 * and that is controlled by learndash.
 *
 * @package    Threat_Scholar
 * @subpackage Threat_Scholar/public
 * @author     Steve Giorgi <steve@dotekimedia.com>
 */
namespace AmpopupLearn\Views\Partials;

use AmpopupLearn\Helpers\PostHelper;

class AmView {

    var $plugin_name = "amview";

    public function __construct( $noenqueue=false ) {
        // $this->enqueue_scripts();
        // $this->enqueue_styles();
    }

     /**
     * get report of dashboard data
     *
     * @return void
     */
    public function get_report(){
        $transient_data = array();

        $data_headers						    	=	array();
        $data_headers['user_id']  					= 	array( 
                                                                    'label'		=>	esc_html__( 'user_id', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );
        $data_headers['user_name'] 					= 	array( 
                                                                    'label'		=>	esc_html__( 'name', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );

        $data_headers['user_email'] 					=	array( 
                                                                    'label'		=>	esc_html__( 'email', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );
                                                                
        $data_headers['course_id'] 					= 	array( 
                                                                    'label'		=>	esc_html__( 'course_id', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );
        $data_headers['course_title'] 				= 	array( 
                                                                    'label'		=>	esc_html__( 'course_title', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );

        $data_headers['course_steps_completed'] 		= 	array( 
                                                                    'label'		=>	esc_html__( 'steps_completed', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );
        $data_headers['course_steps_total'] 			= 	array( 
                                                                    'label'		=>	esc_html__( 'steps_total', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );
        $data_headers['course_completed'] 			= 	array( 
                                                                    'label'		=>	esc_html__( 'course_completed', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );
        $data_headers['course_completed_on']			=	array( 
                                                                    'label'		=>	esc_html__( 'course_completed_on', 'learndash' ),
                                                                    'default'	=>	'',
                                                                    'display'	=>	array( $this, 'report_column' )
                                                                );
        $data=[];
        // $response = \rest_ensure_response( $data ); 
        

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
                                    // $row = array();
                                    
                                    // foreach( $data_headers as $header_key => $header_data ) {
                                    
                                    //     if ( ( isset( $header_data['display'] ) ) && ( !empty( $header_data['display'] ) ) ) {
                                    //         $row[$header_key] = \call_user_func_array( $header_data['display'], array(
                                    //                 'header_value'	=>	$header_data['default'],
                                    //                 'header_key'	=>	$header_key, 
                                    //                 'item' 			=> 	$result, 
                                    //                 'report_user' 	=> 	$report_user,
                                    //             ) 
                                    //         );
                                    //     } else if ( ( isset( $header_data['default'] ) ) && ( !empty( $header_data['default'] ) ) ) {
                                    //         $row[$header_key] = $header_data['default'];
                                    //     } else {
                                    //         $row[$header_key] = '';
                                    //     }
                                    // }
                                    
                                    // if ( !empty($row ) ) {
                                        // $course_progress_data[] = $row;
                                        $course_progress_data[] = $result;
                                    // }
                                }
                            } else {
                                
                                if ( ( is_array( $transient_data['activity_status'] ) ) 
                                    && ( count( $transient_data['activity_status'] ) > 1 ) 
                                    && ( in_array('NOT_STARTED', $transient_data['activity_status'] ) ) ) {
                                    
                                    // $row = array();
                            
                                    // foreach( $data_headers as $header_key => $header_data ) {
                                
                                    //     if ( ( isset( $header_data['display'] ) ) && ( !empty( $header_data['display'] ) ) ) {
                                    //         $row[$header_key] = \call_user_func_array( $header_data['display'], array(
                                    //                 'header_value'	=>	$header_data['default'],
                                    //                 'header_key'	=>	$header_key,
                                    //                 'item' 			=> 	new stdClass(), 
                                    //                 'report_user' 	=> 	$report_user,
                                    //             ) 
                                    //         );
                                    //     } else if ( ( isset( $header_data['default'] ) ) && ( !empty( $header_data['default'] ) ) ) {
                                    //         $row[$header_key] = $header_data['default'];
                                    //     } else {
                                    //         $row[$header_key] = '';
                                    //     }
                                    // }

                                    // if ( !empty($row ) ) {
                                        // $course_progress_data[] = $row;
                                        $course_progress_data[] = $report_user;
                                    // }
                                }
                            }
                        }
                    }
                       
                }
                   
                // if ( !empty( $course_progress_data ) ) {
                    $data['progress'] = $course_progress_data;
                // }
            } 
            
            $data['result_count'] 		= 	$data['total_count'] - count( $transient_data['users_ids'] );
            $data['progress_percent'] 	= 	( $data['result_count'] / $data['total_count'] ) * 100;
            // $data['progress_label']		= 	sprintf( esc_html_x('%1$d of %2$s Users', 'placeholders: result count, total count', 'learndash'), $data['result_count'], $data['total_count']);
        }
 
        // $response = \rest_ensure_response( $data ); 
        // return $response;
        wp_send_json( $data );
        die();
    }

    /**
     * register API REST route
     *
     * @return void
     */
    public function register_api(){
        register_rest_route( 'ampopmusic/v1', '/report', array(
            'methods'  => 'POST',
            'callback' => function(){
                return PostHelper::get_report();
            }
        ) );
    }

    
    
    /**
     * run shortcode and echo print content
     *
     * @return void
     */
    public function run(){
        // start output html
        $this->print_begin();

        // print content
        $this->print_content();

        // finish and close all html blocks
        $this->print_end();
    }

    /**
     * print to out all with start blocks
     *
     * @return void
     */
    private function print_begin(){
        ?>
        <div id="ampopmusiclearn" class="bg-light w-100 h-100">
        <?php


        // print search bar
        $this->print_searchbar();

        // print head with music menu
        $this->print_head();
    }

    /**
     * Print serach bar
     *
     * @return void
     */
    private function print_searchbar(){
        ?>
        <div class="input-group mb-3 searchbar">
            <div class="input-group-prepend searchbar__prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-search"></i></span>
            </div>
            <input type="text" class="form-control searchbar__input" v-model="query" placeholder="Search for student">
        </div>
        <?php
    }

    /**
     * function for print header
     *
     * @return void
     */
    private function print_head(){
        // get music categories or tab
        $tabs = PostHelper::get_music_categories(true);
        ?>
            <div class="d-flex justify-content-between align-content-between flex-nowrap ampoptabs">
                <?php
                    foreach ($tabs as $tab){
                        ?>
                        <button class="btn btn-primary ampoptabs__button"><?=$tab['title']?></button>
                        <?php
                    }
                ?>
            </div>
        <?php
    }

    /**
     * show all content with certs
     *  @table - flag to export pdf and table
     * @return void
     */    
    private function print_content( ){
        // print students block
        $this->print_students();
    }

    /**
     * Print students block
     *
     * @return void
     */
    private function print_students(){
        ?>
        <div class="students">
            <div class="students__head d-flex justify-content-between align-content-between">
                <h1>Students <div class="badge badge-light students__head--badge">12</div> </h1>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary students__head--button"><i class="fas fa-file-pdf"></i> PDF </button>
                    <button class="btn btn-primary students__head--button ml-4"><i class="fas fa-envelope"></i> Email </button>
                    
                    <div class="input-group ml-4 students__head--group">
                        <div class="input-group-prepend students__head--prepend">
                            <span class="input-group-text" >FROM:</span>
                        </div>
                        <input type="date" class="form-control students__head--input" v-model="datefrom" placeholder="Date" value="04-24-2019">
                        <div class="input-group-append students__head--append">
                            <span class="btn btn-primary students__head--button" ><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>  

                    <div class="input-group ml-4 students__head--group">
                        <div class="input-group-prepend students__head--prepend">
                            <span class="input-group-text" >TO:</span>
                        </div>
                        <input type="date" class="form-control students__head--input" v-model="dateto" placeholder="Date" value="04-24-2020">
                        <div class="input-group-append students__head--append">
                            <span class="btn btn-primary students__head--button" ><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>  
                </div>
            </div>

            <hr class="students__head--hr d-shadow">

            <div class="students__table">
                <!-- Table header -->
                <div class="students__table--header">
                    <div class="header__student active">Student <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                    <div class="header__points">Points <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                    <div class="header__percent">Percent completed <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                    <div class="header__date">Start Date <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                    <div class="header__login">Login count <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                    <div class="header__time">Course Time <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                    <div class="header__laststep">Latest Course Step <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                    <div class="header__lastlogin">Last Login Date<span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                    <div class="header__contact">Contact <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                </div>
                <!-- Table body -->
                <div class="students__table--body">
                    <!-- Table complex row -->
                    <div class="students__table--row active">
                        <!-- Table row short sortable data -->
                        <div class="students__table--rowshort">
                            <div class="row__student">      
                                <div class="row__student--avatar">
                                    <img src="https://via.placeholder.com/30" alt="">
                                </div>
                                Anna K.
                            </div>
                            <div class="row__points">  6580</div>
                            <div class="row__percent"> 
                                <div class="percentbar active" data-percentage="81">
                                    <span style="width: 81%"></span>
                                </div>
                            </div>
                            <div class="row__date">   01-15-2019</div>
                            <div class="row__login">  87</div>
                            <div class="row__time">   28:15:00</div>
                            <div class="row__laststep d-flex flex-column"> 
                                <div class="title"> Jass Study Unit </div>
                                <div class="subtitle"> Chapter 7 : Big Band - Jazz Swi </div>
                            </div>
                            <div class="row__lastlogin"> 05-17-2020</div>
                            <div class="row__contact"> 
                                <button class="btn btn-primary row__button"><i class="fas fa-envelope"></i> Email</button>
                            </div>
                        </div>
                        <!-- Table row extra full data -->
                        <div class="students__table--rowextra d-flex justify-content-between align-content-between">
                            <div class="rowextra__quiz">
                                <div class="title">Quiz Status</div>
                                <div class="rowextra__quiz--chapters">

                                    <div class="rowextra__quiz--chapter">
                                        <div class="rowextra__quiz--chapterhead">Chapter 1 Quiz</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored">7</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar  green " data-percentage="78">
                                                        <span style="width: 78%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">3-2-2020</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">1:25:34</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rowextra__quiz--chapter">
                                        <div class="rowextra__quiz--chapterhead">Chapter 1 Quiz</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored">7</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar green " data-percentage="78">
                                                        <span style="width: 78%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">3-2-2020</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">1:25:34</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rowextra__quiz--chapter red">
                                        <div class="rowextra__quiz--chapterhead">Chapter 1 Quiz</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored">7</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar red " data-percentage="78">
                                                        <span style="width: 78%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">3-2-2020</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">1:25:34</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rowextra__quiz--chapter red">
                                        <div class="rowextra__quiz--chapterhead">Chapter 1 Quiz</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored">7</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar red " data-percentage="78">
                                                        <span style="width: 78%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">3-2-2020</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">1:25:34</div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="rowextra__course">
                                <div class="title">Course Status</div>
                                <div class="rowextra__course--courses ">

                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr class="students__head--hr d-shadow">
                    </div>

                    <!-- Table complex row -->
                    <div class="students__table--row">
                        <!-- Table row short sortable data -->
                        <div class="students__table--rowshort">
                            <div class="row__student">      
                                <div class="row__student--avatar">
                                    <img src="https://via.placeholder.com/30" alt="">
                                </div>
                                Anna K.
                            </div>
                            <div class="row__points">  6580</div>
                            <div class="row__percent"> 
                                <div class="percentbar active" data-percentage="81">
                                    <span style="width: 81%"></span>
                                </div>
                            </div>
                            <div class="row__date">   01-15-2019</div>
                            <div class="row__login">  87</div>
                            <div class="row__time">   28:15:00</div>
                            <div class="row__laststep d-flex flex-column"> 
                                <div class="title"> Jass Study Unit </div>
                                <div class="subtitle"> Chapter 7 : Big Band - Jazz Swi </div>
                            </div>
                            <div class="row__lastlogin"> 05-17-2020</div>
                            <div class="row__contact"> 
                                <button class="btn btn-primary row__button"><i class="fas fa-envelope"></i> Email</button>
                            </div>
                        </div>
                        <!-- Table row extra full data -->
                        <div class="students__table--rowextra d-flex justify-content-between align-content-between">
                            <div class="rowextra__quiz">
                                <div class="title">Quiz Status</div>
                                <div class="rowextra__quiz--chapters">

                                    <div class="rowextra__quiz--chapter">
                                        <div class="rowextra__quiz--chapterhead">Chapter 1 Quiz</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored">7</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar  green " data-percentage="78">
                                                        <span style="width: 78%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">3-2-2020</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">1:25:34</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rowextra__quiz--chapter">
                                        <div class="rowextra__quiz--chapterhead">Chapter 1 Quiz</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored">7</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar green " data-percentage="78">
                                                        <span style="width: 78%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">3-2-2020</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">1:25:34</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rowextra__quiz--chapter red">
                                        <div class="rowextra__quiz--chapterhead">Chapter 1 Quiz</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored">7</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar red " data-percentage="78">
                                                        <span style="width: 78%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">3-2-2020</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">1:25:34</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="rowextra__quiz--chapter red">
                                        <div class="rowextra__quiz--chapterhead">Chapter 1 Quiz</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored">7</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar red " data-percentage="78">
                                                        <span style="width: 78%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">3-2-2020</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">1:25:34</div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="rowextra__course">
                                <div class="title">Course Status</div>
                                <div class="rowextra__course--courses ">

                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rowextra__course--course ">
                                        <div class="rowextra__course--chapterhead">Course 1</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar blue" data-percentage="81">
                                                        <span style="width: 81%"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <hr class="students__head--hr d-shadow">
                    </div>
                </div>

            </div>

        </div>
        <?php
    }


    /**
     * Print end blocks
     *
     * @return void
     */
    private function print_end(){
        ?>
            </div>
        <?php
    }

    /**
     * [enqueue_scripts jquery and validate
     * @return [type] [description]
     */
    public function enqueue_scripts(){  
        wp_enqueue_script( 'vue', plugin_dir_url( __FILE__ ) . '../js/vendor/vue.js', [], '2.6.11', false );
		wp_enqueue_script( 'ampoppublic', plugin_dir_url( __FILE__ ) . '../js/ampopuplearn-public.js', array( 'vue' ), '1.0', false );
        wp_localize_script( 'ampoppublic', 'ampoppublic_params', array( 
            'userid' 	=> 	\get_current_user_id(  ), 
            )
        );
        //     'ajaxurl'	=>	\admin_url( 'admin-ajax.php' ),
        //     'template_load_delay' => \apply_filters('ld_propanel_js_template_load_delay', 1000),
        // ) 
        // );
    }

    /**
     * [enqueue_scripts jquery and validate
     * @return [type] [description]
     */
    public function enqueue_styles(){  
		wp_enqueue_style( 'ampoppublic_style', plugin_dir_url( __FILE__ ) . '../css/ampopuplearn-public.css', array(), '1.0', 'all' );
    }

}