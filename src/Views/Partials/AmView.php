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
        $category_id = (int)($_POST['category_id']);
        $data = PostHelper::get_category_students($category_id);
        // $response = \rest_ensure_response( $data ); 
        // return $response;
        wp_send_json([ 'data'=> $data, 'category_id'=>$category_id] );
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
                return PostHelper::get_category_students();
            }
        ) );
    }

    
    
    /**
     * run shortcode and echo print content
     *
     * @return void
     */
    public function run(){

        if ( $this->check_user_is_leader() ){

            // start output html
            $this->print_begin();

            // print content
            $this->print_content();

            // finish and close all html blocks
            $this->print_end();
        }else{
            echo "<script> window.location.href='/members'; </script>"; 
        }
    }

    /**
     * check if user in leader group
     */
    public function check_user_is_leader(){
        $user_id = get_current_user_id(  );

        $leader_groups = LeardashGroups();
        // print_r($leader_groups);

        foreach ($leader_groups as $_l){
            if (isset($_l['groupleaders']) ){
                foreach ($_l['groupleaders'] as $_gl){
                    if ($user_id == $_gl->ID) {
                        // print_r($_gl);
                        return true;
                    }
                }
            }
        }

        return false;        
    }

    /**
     * print to out all with start blocks
     *
     * @return void
     */
    private function print_begin(){
        ?>

        <div id="ampopmusiclearn" class="bg-light w-100 h-100">
            <div class="dashboard__container">
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
        $image_url = '';

        ?>
            <div id="swiper" class="d-flex justify-content-between align-content-between flex-nowrap ampoptabs">
                <vue-swiper inline-template :active_category="active_category">
                    <div class="swiper__container">

                        <div class="swiper-wrapper">

                            <?php foreach ($tabs as $tab) : 
                                    $image_url = get_field('background', $tab['id']);
                                ?>
                                <div class="swiper-slide" style="background: url('<?=$image_url?>');" v-bind:class="{active:active_category==<?=$tab['id']?>}" v-on:click="setCategory('<?=$tab['id']?>')">
                                    <!--<button class="btn btn-primary ampoptabs__button"
                                        v-bind:class="{active:active_category==<?=$tab['id']?>}"
                                        v-on:click="setCategory('<?=$tab['id']?>')"><?=$tab['title']?>                                            
                                    </button>-->
                                    <div class="swiper__content">
                                        <h3 class="swiper__title"
                                            v-bind:class="{active:active_category==<?=$tab['id']?>}"
                                            v-on:click="setCategory('<?=$tab['id']?>')"><?=$tab['title']?>                                            
                                        </h3>
                                    </div>

                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>                         
                </vue-swiper>           
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
                <h1>Students <div class="badge badge-light students__head--badge">{{students.length}}</div> </h1>

                
                <?php
                    
                    $groups = PostHelper::get_admin_groups();
                    // print_r($groups);

                    if ( is_array($groups) && count($groups)>0 ){
                        ?>
                        
                        <select class="students__select" name="select" v-model="groupid" @change="loadUsers"  :run="!groupid?groupid=<?=array_shift(array_slice($groups,0,1))?>:groupid">
                            <?php 
                            $_f = 0;
                            foreach ($groups as $id){ ?>
                                <option value="<?=$id?>" ><?=get_the_title($id)?></option>
                            <?php 
                            }
                            ?>
                        </select>
                        <?php
                    }

                    /* Query Groups
                    foreach($group_ids as $group_id) {
                        echo $group_id;
                        $args = array(
                            'post_type'         =>  'groups',
                            'posts_per_page'    =>  -1,
                            'post__in'          =>  $group_id
                        );
                        
                        
                        $query = new \WP_Query( $args );

                        print_r($query);
                            wp_reset_postdata();                        
                    }
                    */
                ?>



                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary students__head--button"><i class="fas fa-file-pdf"></i> PDF </button>
                    <button class="btn btn-primary students__head--button ml-4"><i class="fas fa-envelope"></i> Email </button>
  
                    <div class="input-group ml-4 students__head--group">
                        <div class="input-group-prepend students__head--prepend">
                            <span class="input-group-text" >FROM:</span>
                        </div>
                        <input type="date" class="form-control students__head--input" v-model="datefrom" placeholder="Date" value="04-24-2019" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
                        <div class="input-group-append students__head--append">
                            <span class="btn btn-primary students__head--button" ><i class="fas fa-chevron-down"></i></span>
                        </div>
                    </div>  

                    <div class="input-group ml-4 students__head--group">
                        <div class="input-group-prepend students__head--prepend">
                            <span class="input-group-text" >TO:</span>
                        </div>
                        <input type="date" class="form-control students__head--input" v-model="dateto" v-bind:min="datefrom" placeholder="Date" value="04-24-2020" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
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
                    <div class="header__student"    
                         v-bind:class="{active:sort=='name'}"
                         v-on:click="setSort('name')">Student <span class="action__sort"><i class="fas " v-bind:class="{'fa-arrow-up':!reversesort, 'fa-arrow-down':reversesort }" ></i></span> </div>
                    <div class="header__points"     
                         v-bind:class="{active:sort=='points'}"
                         v-on:click="setSort('points')">Points <span class="action__sort"><i class="fas" v-bind:class="{'fa-arrow-up':!reversesort, 'fa-arrow-down':reversesort }" ></i></span> </div>
                    <div class="header__percent"    
                         v-bind:class="{active:sort=='percents'}"
                         v-on:click="setSort('percents')">Percent completed <span class="action__sort"><i class="fas" v-bind:class="{'fa-arrow-up':!reversesort, 'fa-arrow-down':reversesort }" ></i></span> </div>
                    <div class="header__date"   
                         v-bind:class="{active:sort=='startDate'}"
                         v-on:click="setSort('startDate')">Start Date <span class="action__sort"><i class="fas" v-bind:class="{'fa-arrow-up':!reversesort, 'fa-arrow-down':reversesort }" ></i></span> </div>
                    <div class="header__login"      
                         v-bind:class="{active:sort=='loginCount'}"
                         v-on:click="setSort('loginCount')">Login count <span class="action__sort"><i class="fas" v-bind:class="{'fa-arrow-up':!reversesort, 'fa-arrow-down':reversesort }" ></i></span> </div>
                    <div class="header__time"   
                         v-bind:class="{active:sort=='courseTime'}"
                         v-on:click="setSort('courseTime')">Course Time <span class="action__sort"><i class="fas" v-bind:class="{'fa-arrow-up':!reversesort, 'fa-arrow-down':reversesort }" ></i></span> </div>
                    <div class="header__laststep"   
                         v-bind:class="{active:sort=='lastStepUnit'}"
                         v-on:click="setSort('lastStepUnit')">Latest Course Step <span class="action__sort"><i class="fas" v-bind:class="{'fa-arrow-up':!reversesort, 'fa-arrow-down':reversesort }" ></i></span> </div>
                    <div class="header__lastlogin"      
                         v-bind:class="{active:sort=='lastLoginLdate'}"
                         v-on:click="setSort('lastLoginLdate')">Last Login Date<span class="action__sort"><i class="fas" v-bind:class="{'fa-arrow-up':!reversesort, 'fa-arrow-down':reversesort }" ></i></span> </div>
                    <div class="header__contact" >Contact <span class="action__sort"><i class="fas fa-arrow-up"></i></span> </div>
                </div>
                <!-- Table body -->
                <div class="students__table--body">
                    <!-- Table complex row -->
                    <div class="students__table--row" 
                         v-for="student in students_prepared"
                         v-bind:class="{active:student.active}"
                         @click="student.active=!student.active" >
                        <!-- Table row short sortable data -->
                        <div class="students__table--rowshort">
                            <div class="row__student">      
                                <div class="row__student--avatar">
                                    <img v-bind:src="student.avatar" alt="">
                                </div>
                                {{student.name}}
                            </div>
                            <div class="row__points">  {{student.points}}</div>
                            <div class="row__percent"> 
                                <div class="percentbar " 

                                     v-bind:class="getColorOfPercent(student.percents)"
                                     :data-percentage="student.percents">
                                    <span :style="{width: student.percents+'%'}"></span>
                                </div>
                            </div>
                            <div class="row__date">   {{student.startDate}}</div>
                            <div class="row__login">  {{student.loginCount}}</div>
                            <div class="row__time">   {{student.courseTime}}</div>
                            <div class="row__laststep d-flex flex-column"> 
                                <div class="title"> {{student.lastStepUnit}} </div>
                                <div class="subtitle"> {{student.lastStepChapter}} </div>
                            </div>
                            <div class="row__lastlogin"> {{student.lastLoginLdate}}</div>
                            <div class="row__contact"> 
                                <button class="btn btn-primary row__button" :href="'mailto:'+student.email"><i class="fas fa-envelope"></i> Email</button>
                            </div>
                        </div>
                        <!-- Table row extra full data -->
                        <div class="students__table--rowextra d-flex justify-content-between align-content-between"
                             v-if="student.quiz.length>0">
                            <div class="rowextra__quiz">
                                <div class="title">Quiz Status</div>
                                <div class="rowextra__quiz--chapters">

                                    <div class="rowextra__quiz--chapter"
                                         v-for="quiz in student.quiz"
                                         v-bind:class="getColorOfPercent(quiz.score)">
                                        <div class="rowextra__quiz--chapterhead">{{quiz.name}}</div>
                                        <div class="rowextra__quiz--chapterbody">
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Attemts</div>
                                                <div class="rowextra__quiz--coldata colored"
                                                     >{{quiz.attempts}}</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Last Score (%)</div>
                                                <div class="rowextra__quiz--coldata colored">
                                                    <div class="percentbar  " 
                                                         v-bind:class="getColorOfPercent(quiz.score)"
                                                         :data-percentage="quiz.score" >
                                                        <span :style="{width: quiz.score+'%'}"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Date</div>
                                                <div class="rowextra__quiz--coldata colored">{{quiz.date}}</div>
                                            </div>
                                            <div class="rowextra__quiz--chaptercol d-flex flex-column">
                                                <div class="rowextra__quiz--coltitle">Duration</div>
                                                <div class="rowextra__quiz--coldata colored">{{quiz.duration}}</div>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="rowextra__course"
                                 v-if="student.courses.length>0"> 

                                <div class="title">Course Status</div>
                                <div class="rowextra__course--courses ">

                                    <div class="rowextra__course--course "
                                         v-for="(course) in student.courses">
                                        <div class="rowextra__course--chapterhead">{{course.name}}</div>
                                        <div class="rowextra__course--coursebody">
                                            <div class="rowextra__course--coursecol d-flex flex-column">
                                                <div class="rowextra__course--coldata ">
                                                    <div class="percentbar  " 
                                                         :data-percentage="course.score"
                                                         v-bind:class="getColorOfPercent(course.score)">
                                                        <span :style="{width: course.score+'%'}"></span>
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
            </div>
        <?php
    }

    /**
     * [enqueue_scripts jquery and validate
     * @return [type] [description]
     */
    public function enqueue_scripts(){  

        wp_enqueue_script( 'vue', plugin_dir_url( __FILE__ ) . '../js/vendor/vue.js', [], '2.6.11', false );
        wp_enqueue_script( 'htppVueLoader', plugin_dir_url( __FILE__ ) . '../js/vendor/httpVueLoader.js', [], '', false );
        wp_enqueue_script( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js', [], '', false );
        wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@5.3.6/js/swiper.min.js', [], '', false );
        wp_enqueue_script( 'vue-awesome-swiper', 'https://cdn.jsdelivr.net/npm/vue-awesome-swiper', [], '', false );
		wp_enqueue_script( 'ampoppublic', plugin_dir_url( __FILE__ ) . '../js/ampopuplearn-public.js', array( 'vue' ), '1.0', false );
        wp_localize_script( 'ampoppublic', 'ampoppublic_params', array( 
            'userid' 	=> 	\get_current_user_id(  ), 
            'nonce' => wp_create_nonce('usin_global'),
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
        wp_enqueue_style( 'ampoppublic_style', plugin_dir_url( __FILE__ ) . '../css/style.css', array(), '1.0', 'all' ); // update was for ease of use, I think you're taking advantage of a workspace which I need to try
        wp_enqueue_style( 'vuetify', 'https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css', array(), '', 'all' );
        wp_enqueue_style( 'material-design-icons', 'https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css', array(), '', 'all' );
        wp_enqueue_style( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@5.3.6/css/swiper.min.css', array(), '', 'all' );
    }

}