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
}