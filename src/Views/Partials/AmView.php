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
        $this->enqueue_scripts();
        $this->enqueue_styles();
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
                <h1>Students <span class="badge badge-light students__head--badge">12</span> </h1>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary students__head--button"><i class="fas fa-file-pdf"></i> PDF </button>
                    <button class="btn btn-primary students__head--button"><i class="fas fa-envelope"></i> Email </button>
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
            <div class="end">end</div>

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
    }

    /**
     * [enqueue_scripts jquery and validate
     * @return [type] [description]
     */
    public function enqueue_styles(){  
		wp_enqueue_style( 'ampoppublic_style', plugin_dir_url( __FILE__ ) . '../css/ampopuplearn-public.css', array(), '1.0', 'all' );
    }

}