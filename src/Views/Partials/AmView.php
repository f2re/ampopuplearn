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