<?php
/**
 * Plugin Name: Custom Jobs Shortcode
 * Description: Use a custom post type called Jobs and display the posts and their custom fields on the frontend using the [jobs] shortcode. As well as filter based on category using [jobs category="categoryname"].
 * Version: 0.7
 * Author: Anthony Raudino
 * Author URI: https://yourwebsite.com
 */

function jobs_shortcode($atts) {
    $atts = shortcode_atts( array(
        'category' => '',
    ), $atts );

    $args = array(
        'post_type' => 'jobs',
        'posts_per_page' => -1,
    );

    if ( $atts['category'] !== 'all' ) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $atts['category'],
            ),
        );
    }

    $jobs = new WP_Query( $args );

    $output = '<ul ="jobadder-job-post-block">';
    while ( $jobs->have_posts() ) {
        
        $output .= '<li class="jobadder-job-post">';

        
        
        $jobs->the_post();
        
        
       $job_cat = get_post_meta( get_the_ID(), 'job_category', true );
        if ( ! empty( $job_cat ) ) {
             $output .= '<p class="jobadder-job-post jobadder-job-category-description jobadder-job-category-' . $job_cat . '">' . $job_cat . '</p>';
        }
        
        $output .= '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';

        $job_location = get_post_meta( get_the_ID(), 'job_location', true );
        if ( ! empty( $job_location ) ) {
            $output .= '<p class="jobadder-job-post jobadder-job-post-location"><strong>' . $job_location . '</strong></p>';
        }
        
        $job_salary = get_post_meta( get_the_ID(), 'salary', true );
        if ( ! empty( $job_salary ) ) {
            $output .= '<p class="jobadder-job-post jobadder-job-post-salary"><strong>$' . $job_salary . '</strong></p>';
        }
        
        $output .= '<p class="jobadder-job-post-summary-block">';
        $job_summary1 = get_post_meta( get_the_ID(), 'summary1', true );
        if ( ! empty( $job_summary1 ) ) {
            $output .= '<span class="jobadder-job-post jobadder-job-post-summary jobadder-job-post-summary1">' . $job_summary1 . '</span>';
        }
        $job_summary2 = get_post_meta( get_the_ID(), 'summary2', true );
        if ( ! empty( $job_summary2 ) ) {
            $output .= '<span class="jobadder-job-post jobadder-job-post-summary jobadder-job-post-summary2">' . $job_summary2 . '</span>';
        }
        $job_summary3 = get_post_meta( get_the_ID(), 'summary3', true );
        if ( ! empty( $job_summary3 ) ) {
            $output .= '<span class="jobadder-job-post jobadder-job-post-summary jobadder-job-post-summary3">' . $job_summary3 . '</span>';
        }
        $output .= '</p>';



       /* $output .= '<p class="jobadder-job-post jobadder-job-post-content">' . get_the_content() . '</p></li>'; */
       $output .= '</li>'; 
    }
    $output .= '</ul>';

    wp_reset_postdata();

    return $output;
}
add_shortcode( 'jobs', 'jobs_shortcode' );



// Add settings page to WordPress dashboard
function jobs_plugin_settings() {
    add_options_page(
        'Jobs Plugin Settings',
        'Jobs Settings',
        'manage_options',
        'jobs-plugin-settings',
        'jobs_plugin_settings_page',
        'dashicons-admin-settings',
        80
    );
}
add_action( 'admin_menu', 'jobs_plugin_settings' );

// Settings page content
function jobs_plugin_settings_page() {
    ?>
    <div class="wrap">
        <h1>Jobs Display Post Info</h1>
        <p>[jobs] is the shortcode. To filter using a category, use [jobs category="categoryname"]</p>
    </div>
    <?php
}
