<?php





add_action('admin_enqueue_scripts', 'mw_enqueue_color_picker');

function mw_enqueue_color_picker($hook_suffix)

{

    // first check that $hook_suffix is appropriate for your admin page

    wp_enqueue_style('wp-color-picker');

    wp_enqueue_script('my-script-handle', plugins_url('my-script.js', __FILE__), array('wp-color-picker'), false, true);
}



//create custom taxonomy tag for questions 

if (!function_exists('cpm_custom_tags_question_taxonomy')) {

    add_action('init', 'cpm_custom_tags_question_taxonomy');

    function cpm_custom_tags_question_taxonomy()

    {



        $labels = array(

            'name'              => _x('Question Tags', 'taxonomy general name'),

            'singular_name'     => _x('Question Tag', 'taxonomy singular name'),

            'search_items'      => __('Search Question Tags'),

            'all_items'         => __('All Question Tags'),

            'parent_item'       => __('Parent Question Tag'),

            'parent_item_colon' => __('Parent Question Tag:'),

            'edit_item'         => __('Edit Question Tag'),

            'update_item'       => __('Update Question Tag'),

            'add_new_item'      => __('Add New Question Tag'),

            'new_item_name'     => __('New Question Tag Name'),

            'menu_name'         => __('Question Tags'),

        );



        $args = array(

            'hierarchical'      => false,

            'labels'            => $labels,

            'show_ui'           => true,

            'show_admin_column' => true,

            'query_var'         => true,

            'rewrite'           => array('slug' => 'question_tag'),

        );



        register_taxonomy('question_tag', 'sfwd-question', $args);
    }
}





// hide display from edit page

if (!function_exists('hide_custom_taxonomy_meta_box')) {

    add_action('admin_menu', 'hide_custom_taxonomy_meta_box');

    function hide_custom_taxonomy_meta_box()

    {

        remove_meta_box('tagsdiv-question_tag', 'sfwd-question', 'normal');
    }
}





// add custom html to questions

if (!function_exists('js_ajax_load_buttons_tags_save')) {
    add_action('wp_footer', 'js_ajax_load_buttons_tags_save');
    function js_ajax_load_buttons_tags_save()
    {
        if (is_user_logged_in()) {

            $taxonomy = 'question_tag';

            $terms = get_terms([

                'taxonomy'   => $taxonomy,

                'hide_empty' => false,

            ]);

            if (!empty($terms)) {

                // echo '<div class="cpm-Qtags-group">';

                foreach ($terms as $term) {

                    $slug = $term->slug;

                    $name = $term->name;

                    $term_color = get_term_meta($term->term_id, 'question_term_color', true);

?>
                    <style>
                        <?php echo '.cpm-' . $slug; ?> {

                            background-color: <?php echo $term_color; ?> !important
                        }

                        .learndash-wrapper .wpProQuiz_content <?php echo '.cpm-' . $slug; ?>:hover {
                            border: 2px solid <?php echo $term_color ?>;
                        }
                    </style>

                    <?php

                    // list($r, $g, $b) = sscanf($term_color, "#%02x%02x%02x");

                    $meta_values = get_user_meta(get_current_user_id(), $slug, true);

                    if ($meta_values != '') {

                        $json_array = json_encode($meta_values);
                    } else {

                        $json_array = json_encode([]);
                    }

                    ?>

                    <script>
                        // jQuery(document).ready(function($) {

                        (function($) {

                            // Find all elements with class name 'wpProQuiz_listItem'

                            var listItems = $('.wpProQuiz_listItem');

                            // Loop through each found element

                            listItems.each(function() {

                                var listItem = $(this);

                                // Get the value of 'data-question-meta' attribute

                                var questionMeta = listItem.attr('data-question-meta');

                                var questionMetaObj = JSON.parse(questionMeta);

                                // Extract the value of 'question_post_id'

                                var questionPostId = questionMetaObj.question_post_id;

                                var json_array = '<?php echo $json_array; ?>';

                                json_array = JSON.parse(json_array);

                                var termcol = '<?php echo $term_color ?>';

                                // var termcol = hexToRgb('<?php echo $term_color ?>');

                                var highlight_style = '';
                                var active_class = '';

                                if (json_array) {

                                    $.each(json_array, function(key, value) {

                                        if (key == questionPostId) {

                                            // highlight_style = "box-shadow:0 8px 16px 0 rgba(" + termcol.r + ", " + termcol.g + ", " + termcol.b + ", 0.6),0 6px 20px 0 rgba( " + termcol.r + ", " + termcol.g + ", " + termcol.b + ", 0.2)";
                                            // highlight_style = " color: " + termcol + "; "
                                            // highlight_style = " border: 2px solid " + termcol + "; color: " + termcol + "; background: transparent !important; "
                                            // highlight_style = " border: 2px solid " + termcol + "; color: " + termcol + "; background: transparent !important; "
                                            // highlight_style = 'border: 2px solid ' + termcol + ';'
                                            active_class = "cpm-active-tag"
                                        }

                                    })
                                }

                                // cpm-Qtag-hilight

                                // var newHtml = "<button class='cpm-Qtag med-quiz-btn wpProQuiz_button " + active_class + "  <?php echo 'cpm-' . $slug; ?>' style='" + highlight_style + " ' value='<?php echo $slug ?>' data-termcolor='<?php echo $term_color; ?>' data-pid='" + questionPostId + "' ><div class='cpm-flexdiv'><span class='cpm-tag-image' style='background-color:" + termcol + ";'></span><span  class='cpm-tag-name'><?php echo $name ?></span></div></button>";
                                var newHtml = "<button class='cpm-Qtag med-quiz-btn wpProQuiz_button " + active_class + "  <?php echo 'cpm-' . $slug; ?>' value='<?php echo $slug ?>' data-termcolor='<?php echo $term_color; ?>' data-pid='" + questionPostId + "' ><div class='cpm-flexdiv'><span class='cpm-tag-image' style='background-color:" + termcol + ";'></span><span  class='cpm-tag-name'><?php echo $name ?></span></div></button>";

                                // var newHtml = "<button class='cpm-Qtag wpProQuiz_button <?php // echo 'cpm-' . $slug; 

                                                                                            ?>' value='<?php // echo $slug 

                                                                                                        ?>' data-termcolor='<?php // echo $term_color; 

                                                                                                                            ?>' data-pid='" + questionPostId + "' ><i style='padding-right:5px;' class='fa fa-thin fa-tag'></i><?php // echo $name 

                                                                                                                                                                                                                                ?></button>";



                                // Find 'wpProQuiz_QuestionButton' element inside 'wpProQuiz_listItem'

                                var question_response = listItem.find('.wpProQuiz_response');

                                // Add the new HTML content after 'wpProQuiz_QuestionButton'

                                question_response.after(newHtml);
                            });
                            // });

                        })(jQuery);
                    </script>
                <?php
                }
                ?>
    <?php
                // echo '</div>';
            }
        }
    }
}





if (!function_exists('ajax_save_tag_values')) {

    add_action('wp_ajax_ajax_save_tag_values', 'ajax_save_tag_values');

    function ajax_save_tag_values()

    {

        // Get the value from the AJAX request

        $question_tag = isset($_POST['question_tag']) ? sanitize_text_field($_POST['question_tag']) : '';

        $question_id = isset($_POST['question_id']) ? sanitize_text_field($_POST['question_id']) : '';



        $taxonomy = 'question_tag';

        $terms = get_terms([

            'taxonomy'   => $taxonomy,

            'hide_empty' => false,

        ]);

        $new_values = array($question_id => $question_tag);

        // $new_values = array();

        // $new_values[] = $question_id;



        if (!empty($terms)) {

            foreach ($terms as $term) {

                $slug = $term->slug;

                // $name = $term->name;



                if ($question_tag === $slug) {

                    $meta_values = get_user_meta(get_current_user_id(), $slug, true);



                    if ($meta_values == '') {

                        update_user_meta(get_current_user_id(), $slug, $new_values);
                    } else {

                        $meta_values[$question_id] = $question_tag;

                        // $meta_values[] = $question_id;

                        update_user_meta(get_current_user_id(), $slug, $meta_values);
                    }
                }

                if ($question_tag != $slug) {

                    $meta_values = get_user_meta(get_current_user_id(), $slug, true);



                    if ($meta_values) {

                        foreach ($meta_values as $key => $value) {



                            if ($key == $question_id) {



                                unset($meta_values[$question_id]);

                                update_user_meta(get_current_user_id(), $slug, $meta_values);
                            }
                        }

                        // foreach ($meta_values as $meta_value) {

                        //     if ($meta_value == $question_id) {

                        //         unset($meta_values[$question_id]);

                        //         update_user_meta(get_current_user_id(), $slug, $meta_values);

                        //     }

                        // }

                    }
                }
            }
        }

        // echo $question_id . ' ' . $question_tag;

        wp_die();
    }
}





function add_custom_taxonomy_field()

{

    ?>

    <div class="form-field term-color-wrap">

        <label for="question_term_color">Color</label>

        <input type="text" id="question_term_color" value="#00a2e8" name="question_term_color" class="my-color-field" data-default-color="#00a2e8" />



    </div>

<?php

}

add_action('question_tag_add_form_fields', 'add_custom_taxonomy_field');



function add_custom_taxonomy_field_for_edit($term)

{

    $term_color = get_term_meta($term->term_id, 'question_term_color', true);

?>

    <table class="form-table">

        <tbody>

            <tr class="form-field form-required term-name-wrap form-table">

                <th scope="row"><label for="question_term_color">Color</label></th>

                <td>

                    <input type="text" value="<?php echo $term_color; ?>" id="question_term_color" name="question_term_color" class="my-color-field" data-default-color="#00a2e8" />

                </td>

            </tr>

        </tbody>

    </table>

    <?php

}

add_action('question_tag_edit_form_fields', 'add_custom_taxonomy_field_for_edit');









function save_custom_taxonomy_field($term_id)

{

    if (isset($_POST['question_term_color'])) {

        $term_color = sanitize_hex_color($_POST['question_term_color']);



        update_term_meta($term_id, 'question_term_color', $term_color);
    }
}

add_action('created_question_tag', 'save_custom_taxonomy_field');

add_action('edited_question_tag', 'save_custom_taxonomy_field');





// if (!function_exists('ajax_display_tagged_questions_list')) {

//     add_action('wp_ajax_ajax_display_tagged_questions_list', 'ajax_display_tagged_questions_list');



//     function ajax_display_tagged_questions_list()

//     {

//         // Get the value from the AJAX request

//         $question_tag = isset($_POST['question_tag']) ? sanitize_text_field($_POST['question_tag']) : '';

//         // $question_tag = 'easy';



//         $meta_values = get_user_meta(get_current_user_id(), $question_tag, true);

//         $html = '';



//         if (!empty($meta_values)) {

//             $i = 1;

//             foreach ($meta_values as $key => $value) {



//                 // $html .= '<label class="med-radio-container med-bg" data-id="' . $key . '">';

//                 // if ($i == 1) {

//                 //     $html .= '<label class="med-radio-container med-bg" data-id="' . $key . '"><input type="radio" checked="checked" name="radio" class="med-radio-btn">';

//                 // } else {

//                 $html .= '<label class="med-radio-container" data-id="' . $key . '"><input type="radio" name="radio" class="med-radio-btn">';

//                 // }

//                 $html .= '<p>Question ' . $i . '</p>';

//                 $html .= '<span class="med-checkmark-tag"><i class="fa-solid fa-tag"></i></span> </label>';

//                 $i++;

//             }

//         }

//         echo $html;

//         wp_die();

//     }

// }







function display_single_question($question_id = '')

{

    if (get_post_type($question_id) == 'sfwd-question') {
        $question_type = get_post_meta($question_id, 'question_type', true);
        $question_quiz_id = get_post_meta($question_id, 'quiz_id', true);
        $question_pro_id = get_post_meta($question_id, 'question_pro_id', true);

        global $wpdb;
        $table_name = $wpdb->prefix . 'learndash_pro_quiz_question'; // Replace 'your_table_name' with the actual table name

        $results = $wpdb->get_results("SELECT * FROM $table_name WHERE `id` = $question_pro_id", ARRAY_A, true);
        $results = $results['0'];

        $answer_point_activated = $results['answer_points_activated'];
        $matrix_sort_answer_criteria_width = $results['matrix_sort_answer_criteria_width'];
        $answer_array = $results['answer_data'];
        $answer_array = unserialize($answer_array);

        $question_meta = array(
            'type'             => $question_type,
            'question_pro_id'  => $question_pro_id,
            'question_post_id' => $question_id,
        );

    ?>

        <div class="wpProQuiz_quiz">
            <ol class="wpProQuiz_list">
                <li class="wpProQuiz_listItem" data-type="<?php echo $question_type; ?>" data-question-meta="<?php echo htmlspecialchars(wp_json_encode($question_meta)); ?>">
                    <!-- <div class="wpProQuiz_question_page"> -->
                    <div class="wpProQuiz_question" style="margin: 10px 0px 0px 0px;">
                        <div class="wpProQuiz_question_text">
                            <?php

                            $post_object = get_post($question_id);
                            echo '<p>' . $post_object->post_content . '</p>';
                            // echo '<span> Correct Answer';
                            // var_dump($answer_array);
                            // echo '</span>';
                            ?>
                        </div>
                        <p class="wpProQuiz_clear" style="clear:both;"></p>
                        <?php

                        /**

                         * Matrix Sort Answer

                         */

                        ?>

                        <?php if ($question_type === 'matrix_sort_answer') { ?>
                            <div class="wpProQuiz_matrixSortString">
                                <h5 class="wpProQuiz_header">
                                    <?php
                                    echo wp_kses_post(
                                        SFWD_LMS::get_template(
                                            'learndash_quiz_messages',
                                            array(
                                                'quiz_post_id' => $question_pro_id,
                                                'context'      => 'quiz_question_sort_elements_header',
                                                'message'      => esc_html__('Sort elements', 'learndash'),
                                            )
                                        )
                                    );
                                    ?>
                                </h5>
                                <ul class="wpProQuiz_sortStringList">
                                    <?php
                                    $answer_array_new_matrix = array();
                                    foreach ($answer_array as $q_idx => $q) {
                                        $datapos     = LD_QuizPro::datapos($question_pro_id, $q_idx);
                                        $answer_array_new_matrix[$datapos] = $q;
                                    }

                                    $matrix = array();
                                    foreach ($answer_array as $k => $v) {
                                        $matrix[$k][] = $k;
                                        foreach ($answer_array as $k2 => $v2) {
                                            if ($k != $k2) {
                                                if ($v->getAnswer() == $v2->getAnswer()) {
                                                    $matrix[$k][] = $k2;
                                                } elseif ($v->getSortString() == $v2->getSortString()) {
                                                    $matrix[$k][] = $k2;
                                                }
                                            }
                                        }
                                    }

                                    foreach ($answer_array as $k => $v) {

                                    ?>
                                        <li class="wpProQuiz_sortStringItem" data-pos="<?php echo esc_attr($k); ?>">
                                            <?php echo $v->isSortStringHtml() ? do_shortcode(nl2br($v->getSortString())) : esc_html($v->getSortString()); ?>
                                        </li>
                                    <?php
                                    }

                                    $answer_array = $answer_array_new_matrix;
                                    ?>
                                </ul>
                                <div style="clear: both;"></div>
                            </div>
                        <?php } ?>

                        <?php

                        /**
                         * Print questions in a list for all other answer types
                         */
                        ?>

                        <ul class="wpProQuiz_questionList" data-question_id="<?php echo esc_attr($question_pro_id); ?>" data-type="<?php echo esc_attr($question_type); ?>">
                            <?php

                            if ($question_type === 'sort_answer') {
                                $answer_array_new = array();
                                foreach ($answer_array as $q_idx => $q) {
                                    $datapos                      = LD_QuizPro::datapos($question_pro_id, $q_idx);
                                    $answer_array_new[$datapos] = $q;
                                }
                                $answer_array = $answer_array_new;

                                if ($question_type === 'sort_answer') {
                                    $answer_array_org_keys = array_keys($answer_array);
                                    /**

                                     * Do this while the answer keys match. I just don't trust shuffle to always

                                     * return something other than the original.

                                     */

                                    $random_tries = 0;

                                    while (true) {

                                        // Backup so we don't get stuck because some plugin rewrote a function we are using.

                                        ++$random_tries;



                                        $answer_array_randon_keys = $answer_array_org_keys;

                                        shuffle($answer_array_randon_keys);

                                        $answer_array_keys_diff = array_diff_assoc($answer_array_org_keys, $answer_array_randon_keys);



                                        // If the diff array is not empty or we have reaches enough tries, abort.

                                        if ((!empty($answer_array_keys_diff)) || ($random_tries > 10)) {

                                            break;
                                        }
                                    }



                                    // $answer_array_new = array();

                                    // foreach ($answer_array_randon_keys as $q_idx) {

                                    //     if (isset($answer_array[$q_idx])) {

                                    //         $answer_array_new[$q_idx] = $answer_array[$q_idx];
                                    //     }
                                    // }

                                    // $answer_array = $answer_array_new;
                                }
                            }



                            $answer_index = 0;

                            if (is_array($answer_array)) {

                                foreach ($answer_array as $v_idx => $v) {

                                    $answer_text = $v->isHtml() ? do_shortcode(nl2br($v->getAnswer())) : esc_html($v->getAnswer());
                                    $correct_answer = $v->isHtml() ? do_shortcode(nl2br($v->isCorrect())) : esc_html($v->isCorrect());
                                    $sort_answer = $v->isHtml() ? do_shortcode(nl2br($v->getSortString())) : esc_html($v->getSortString());


                                    if ('' == $answer_text && !$v->isGraded()) {
                                        continue;
                                    }

                                    // var_dump($answer_text);
                                    // echo '<pre>';
                                    // var_dump($v);
                                    // var_dump($sort_answer);
                                    // echo '</pre>';

                                    if ($answer_point_activated) {
                                        $json[$question_pro_id]['points'][] = $v->getPoints();
                                    }

                                    // $datapos = $answer_index;
                                    // if ($question_type === 'sort_answer' || $question_type === 'matrix_sort_answer') {
                                    //     $datapos = $v_idx; // LD_QuizPro::datapos( $question_pro_id, $answer_index );
                                    // }

                                    //correct ans class wpProQuiz_answerCorrect

                            ?>
                                    <li class="wpProQuiz_questionListItem <?php echo ($correct_answer == 1) ? 'cpm-correct-ans' : '';  ?>" data-pos="<?php echo esc_attr($datapos); ?>">

                                        <?php

                                        /**

                                         *  Single/Multiple

                                         */

                                        if ($question_type === 'single' || $question_type === 'multiple') {

                                            $json[$question_pro_id]['correct'][] = (int) $v->isCorrect();

                                        ?>

                                            <!-- <span <?php // echo $quiz->isNumberedAnswer() ? '' : 'style="display:none;"'; 

                                                        ?>></span> -->

                                            <label>

                                                <input class="wpProQuiz_questionInput" autocomplete="off" type="<?php echo $question_type === 'single' ? 'radio' : 'checkbox'; ?>" name="question_<?php echo esc_attr($question_pro_id); ?>_<?php echo esc_attr($question_pro_id); ?>" value="<?php echo esc_attr(($answer_index + 1)); ?>"> <?php echo $answer_text; ?>

                                            </label>

                                        <?php

                                            /**display_single_quiz

                                             *  Sort Answer

                                             */
                                        } elseif ($question_type === 'sort_answer') {

                                            // $json[$question_pro_id]['correct'][] = (int) $answer_index;

                                        ?>

                                            <div class="wpProQuiz_sortable">

                                                <?php echo $answer_text; ?>

                                            </div>

                                        <?php

                                            /**

                                             *  Free Answer

                                             */
                                        } elseif ($question_type === 'free_answer') {

                                            $question = '';

                                            $question_answer_data = learndash_question_free_get_answer_data($v, $question);

                                            // if ((is_array($question_answer_data)) && (!empty($question_answer_data))) {

                                            //     $json[$question_pro_id] = array_merge($json[$question_pro_id], $question_answer_data);
                                            // }

                                        ?>

                                            <label class="cpm-free-ans">

                                                <input class="wpProQuiz_questionInput" type="text" autocomplete="off" name="question_<?php echo esc_attr($question_pro_id); ?>_<?php echo esc_attr($question_pro_id); ?>" style="width: 300px;">

                                                <span class="wpProQuiz_freeCorrect" style="display: none;"><?php echo preg_replace('/\s+/', ', ', $answer_text); ?></span>

                                            </label>

                                        <?php

                                            /**

                                             *  Matrix Sort Answer

                                             */
                                        } elseif ($question_type === 'matrix_sort_answer') {

                                            $json[$question_pro_id]['correct'][] = (int) $answer_index;

                                            $msacw_value = $matrix_sort_answer_criteria_width > 0 ? $matrix_sort_answer_criteria_width : 20;

                                        ?>
                                            <table>

                                                <tbody>

                                                    <tr class="wpProQuiz_mextrixTr">

                                                        <td width="<?php echo esc_attr($msacw_value); ?>%">

                                                            <div class="wpProQuiz_maxtrixSortText"><?php echo $answer_text; ?></div>

                                                        </td>

                                                        <td width="<?php echo esc_attr(100 - $msacw_value); ?>%">

                                                            <ul class="wpProQuiz_maxtrixSortCriterion">
                                                                <li class="wpProQuiz_sortStringItem cpm-sort-element" style="display:none;"><?php echo $sort_answer ?></li>
                                                            </ul>

                                                        </td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                            <?php

                                            /**

                                             *  Cloze Answer

                                             */
                                        } elseif ($question_type === 'cloze_answer') {

                                            $cloze_data   = learndash_question_cloze_fetch_data($v->getAnswer());

                                            $cloze_output = learndash_question_cloze_prepare_output($cloze_data);

                                            $string = trim($answer_text, "{}");
                                            $elements = explode("][", $string);
                                            foreach ($elements as &$element) {
                                                $element = trim($element, "[]");
                                            }
                                            $output = implode(", ", $elements);

                                            echo '<input type="hidden" id="cpm-cloze-ans" value="' . $output . '">';

                                            echo $cloze_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

                                            $json[$question_pro_id]['correct'] = isset($cloze_data['correct']) ? $cloze_data['correct'] : [];

                                            if ($answer_point_activated) {

                                                $json[$question_pro_id]['points'] = $cloze_data['points'];
                                            }

                                            /**

                                             *  Assessment answer

                                             */
                                        } elseif ($question_type === 'assessment_answer') {

                                            $assessment_data = learndash_question_assessment_fetch_data($v->getAnswer(), $question_pro_id, $question_pro_id);

                                            $json[$question_pro_id]['correct'] = isset($assessment_data['correct']) ? $assessment_data['correct'] : [];

                                            if ($answer_point_activated) {

                                                $json[$question_pro_id]['points'] = $assessment_data['points'];
                                            }

                                            $assessment_output = learndash_question_assessment_prepare_output($assessment_data);
                                            // var_dump($answer_text);
                                            // $string = trim($answer_text, "{}");
                                            // $elements = explode("][", $string);
                                            // foreach ($elements as &$element) {
                                            //     $element = trim($element, "[]");
                                            // }
                                            // $answer_text = implode(", ", $elements);
                                            echo '<span id="cpm-asses-ans" style="display:none">Less True ' . $answer_text . ' More True</span>';
                                            echo $assessment_output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Need to output HTML / Shortcodes

                                            /**

                                             * Essay answer

                                             */
                                        } elseif ($question_type === 'essay') {

                                            if ($v->getGradedType() === 'text') :

                                            ?>

                                                <textarea class="wpProQuiz_questionEssay" rows="10" cols="40" name="question_<?php echo esc_attr($question_pro_id); ?>_<?php echo esc_attr($question_pro_id); ?>" id="wpProQuiz_questionEssay_question_<?php echo esc_attr($question_pro_id); ?>_<?php echo esc_attr($question_pro_id); ?>" cols="30" autocomplete="off" rows="10" placeholder="<?php echo wp_kses_post( // phpcs:ignore Squiz.PHP.EmbeddedPhp.ContentBeforeOpen,Squiz.PHP.EmbeddedPhp.ContentAfterOpen

                                                                                                                                                                                                                                                                                                                                                                                                    SFWD_LMS::get_template(

                                                                                                                                                                                                                                                                                                                                                                                                        'learndash_quiz_messages',

                                                                                                                                                                                                                                                                                                                                                                                                        array(

                                                                                                                                                                                                                                                                                                                                                                                                            'quiz_post_id' => $question_pro_id,

                                                                                                                                                                                                                                                                                                                                                                                                            'context'      => 'quiz_essay_question_textarea_placeholder_message',

                                                                                                                                                                                                                                                                                                                                                                                                            'message'      => esc_html__('Type your response here', 'learndash'),

                                                                                                                                                                                                                                                                                                                                                                                                        )

                                                                                                                                                                                                                                                                                                                                                                                                    )

                                                                                                                                                                                                                                                                                                                                                                                                ); ?>"></textarea> <?php // phpcs:ignore Generic.WhiteSpace.ScopeIndent.Incorrect,Squiz.PHP.EmbeddedPhp.ContentBeforeEnd,Squiz.PHP.EmbeddedPhp.ContentAfterEnd,PEAR.Functions.FunctionCallSignature.Indent,PEAR.Functions.FunctionCallSignature.CloseBracketLine 

                                                                                                                                                                                                                                                                                                                                                                                            ?>

                                            <?php elseif ($v->getGradedType() === 'upload') : ?>

                                                <?php

                                                echo wp_kses_post(

                                                    SFWD_LMS::get_template(

                                                        'learndash_quiz_messages',

                                                        array(

                                                            'quiz_post_id' => $question_pro_id,

                                                            'context'      => 'quiz_essay_question_upload_answer_message',

                                                            'message'      => '<p>' . esc_html__('Upload your answer to this question.', 'learndash') . '</p>',

                                                        )

                                                    )

                                                );

                                                ?>

                                                <form enctype="multipart/form-data" method="post" name="uploadEssay">

                                                    <input type='file' name='uploadEssay[]' id='uploadEssay_<?php echo esc_attr($question_pro_id); ?>' size='35' class='wpProQuiz_upload_essay' />

                                                    <input type="submit" id='uploadEssaySubmit_<?php echo esc_attr($question_pro_id); ?>' value="<?php esc_html_e('Upload', 'learndash'); ?>" />

                                                    <input type="hidden" id="_uploadEssay_nonce_<?php echo esc_attr($question_pro_id); ?>" name="_uploadEssay_nonce" value="<?php echo esc_attr(wp_create_nonce('learndash-upload-essay-' . $question_pro_id)); ?>" />

                                                    <input type="hidden" class="uploadEssayFile" id='uploadEssayFile_<?php echo esc_attr($question_pro_id); ?>' value="" />

                                                </form>

                                                <div id="uploadEssayMessage_<?php echo esc_attr($question_pro_id); ?>" class="uploadEssayMessage"></div>

                                            <?php else : ?>

                                                <?php esc_html_e('Essay type not found', 'learndash'); ?>

                                            <?php endif; ?>

                                            <p class="graded-disclaimer">

                                                <?php if ('graded-full' == $v->getGradingProgression()) : ?>

                                                    <?php

                                                    echo wp_kses_post(

                                                        SFWD_LMS::get_template(

                                                            'learndash_quiz_messages',

                                                            array(

                                                                'quiz_post_id' => $question_pro_id,

                                                                'context'      => 'quiz_essay_question_graded_full_message',

                                                                'message'      => esc_html__('This response will be awarded full points automatically, but it can be reviewed and adjusted after submission.', 'learndash'),
                                                            )
                                                        )
                                                    );

                                                    ?>
                                                <?php elseif ('not-graded-full' == $v->getGradingProgression()) : ?>

                                                    <?php
                                                    echo wp_kses_post(
                                                        SFWD_LMS::get_template(
                                                            'learndash_quiz_messages',
                                                            array(
                                                                'quiz_post_id' => $question_pro_id,
                                                                'context'      => 'quiz_essay_question_not_graded_full_message',
                                                                'message'      => esc_html__('This response will be awarded full points automatically, but it will be reviewed and possibly adjusted after submission.', 'learndash'),
                                                            )
                                                        )
                                                    );
                                                    ?>

                                                <?php elseif ('not-graded-none' == $v->getGradingProgression()) : ?>

                                                    <?php
                                                    echo wp_kses_post(
                                                        SFWD_LMS::get_template(
                                                            'learndash_quiz_messages',
                                                            array(
                                                                'quiz_post_id' => $question_pro_id,
                                                                'context'      => 'quiz_essay_question_not_graded_none_message',
                                                                'message'      => esc_html__('This response will be reviewed and graded after submission.', 'learndash'),
                                                            )
                                                        )
                                                    );
                                                    ?>
                                                <?php endif; ?>

                                            </p>

                                        <?php

                                        }

                                        ?>
                                    </li>
                            <?php

                                }
                            }

                            ?>

                        </ul>

                    </div>
                    <div class="cpm-buttn-wrapper">
                        <button name="back" value="Back" class=" wpProQuiz_button med-quiz-btn" id="tagged-single-question-prev" style="float: left ; margin-right: 5px;"><i class="fa-solid fa-angle-left"></i></button>
                        <div class="cpm-flexdiv">
                            <div class="wpProQuiz_response" style="display: none;"></div>
                        </div>
                        <button class="wpProQuiz_button med-quiz-btn" id="tagged-single-question-check-ans">Answer</button>
                        <button name="next" value="Next" class=" wpProQuiz_button med-quiz-btn" id="tagged-single-question-next" style="float: right; margin-right: 5px"><i class="fa-solid fa-angle-right"></i></button>
                    </div>
                </li>
            </ol>
        </div>
        <?php
    }
}





add_action('wp_ajax_ajax_display_single_question', 'ajax_display_single_question');
function ajax_display_single_question()
{
    $question_id = isset($_POST['question_id']) ? sanitize_text_field($_POST['question_id']) : '';
    echo '<div class="wpProQuiz_content">';
    echo display_single_question($question_id);
    echo js_ajax_load_buttons_tags_save();
    echo '</div>';
    wp_die();
}


if (!function_exists('check_focus_mode_enable')) {
    add_action('wp_footer', 'check_focus_mode_enable');
    function check_focus_mode_enable()
    {
        $learndash_settings = get_option('learndash_settings_theme_ld30');
        $focus_mode = $learndash_settings['focus_mode_enabled'];
        if ($focus_mode == 'yes') {
        ?>
            <script>
                jQuery(document).ready(function($) {
                    // Check if the body has the specific class or ID
                    if (window.location.href.indexOf('tagged-questions') > -1) {
                        // if ($('body').hasClass('custom-page-template')) {
                        // Hide the header
                        $('header').hide();
                        $('.elementor-location-header').hide();

                        // elementor-location-header

                        // Hide the footer
                        $('footer').hide();
                        $('.site-footer').hide();

                        // console.log('here');
                    }
                });
            </script>
<?php
        }
    }
}
