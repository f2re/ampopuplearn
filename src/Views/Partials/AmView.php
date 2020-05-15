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
        <div id="ampopmusiclearn" class="bg-white w-100 h-100">
        <?php

        $this->print_head();

        // print search bar
        $this->print_searchbar();
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
        ?>
            
        <?php
    }

    /**
     * show all content with certs
     *  @table - flag to export pdf and table
     * @return void
     */    
    private function print_content( ){
        ?>
            <div class="content">content</div>
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