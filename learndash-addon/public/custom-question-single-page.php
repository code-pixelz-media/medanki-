<?php
/*
Template Name: Question Single Page Template
*/
get_header();
?>
<div class="medanki-wrapper">
    <div class="cpm-breadcrumb-wrapper">
        <div class="med-tag-question">
            <ul class="med-breadcrumb">
                <li><a href="<?php echo home_url(); ?>">Home</a></li>
                <li><?php the_title(); ?></li>
            </ul>
        </div>
    </div>
    <div class="med-focus">
        <div class="med-focus-sidebar mdSidebar <?php echo (wp_is_mobile()) ? 'mdSidebar-collapse' : ''; ?> ">
            <button class="med-focus-sidebar-trigger mdSidebar_trigger">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="med-focus-sidebar-wrapper">
                <div class="med-focus-question-filter">
                    <select name="Questions" id="med-question" class="med-drop-question">
                        <?php
                        $taxonomy = 'question_tag';
                        $terms = get_terms([
                            'taxonomy'   => $taxonomy,
                            'hide_empty' => false,
                        ]);
                        $firstItem = reset($terms);
                        $meta_key = $firstItem->slug;
                        $selectedValue = isset($_GET['tag']) ? $_GET['tag'] : '';
                        if ($selectedValue) {
                            $meta_key = $selectedValue;
                        }
                        $term_color = '';
                        if (!empty($terms)) {
                            foreach ($terms as $term) {
                                $slug = $term->slug;
                                $name = $term->name;
                                if ($selectedValue === $slug) {
                                    $term_color = get_term_meta($term->term_id, 'question_term_color', true);
                                }
                                echo '<option value="' . $slug . '" ' . ($selectedValue === $slug ? 'selected' : '') . '>Etykieta: ' . $name . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="med-focus-radio" id="med-questions-lists-id" data-termcol="<?php echo $term_color; ?>">
                    <?php
                    $meta_values = '';
                    if ($meta_key) {
                        $meta_values = get_user_meta(get_current_user_id(), $meta_key, true);
                    }
                    $firstItem = '';
                    $html = '';
                    $tag_image_url = plugins_url('learndash-addon/public/images/tag.png');
                    if (!empty($meta_values)) {
                        $i = 1;
                        foreach ($meta_values as $key => $value) {
                            // $html .= '<label class="med-radio-container med-bg" data-id="' . $key . '">';
                            if ($i == 1) {
                                $firstItem = $key;
                                $term_color = $term_color;
                                $html .= '<label class="med-radio-container med-bg" data-id="' . $key . '"><input type="radio" checked name="radio" class="med-radio-btn">';
                            } else {
                                $term_color = '';
                                $html .= '<label class="med-radio-container" data-id="' . $key . '"><input type="radio" name="radio" class="med-radio-btn">';
                            }
                            // $term_color = '';
                            $html .= '<p>Pytanie ' . $i . '</p>';
                            $html .= '<span style="background-color:' . $term_color . '" class="med-checkmark-tag"><img src="' . $tag_image_url . '" width="16"></image></span> </label>';
                            // $html .= '<span class="med-checkmark-tag"><i class="fa-solid fa-tag"></i></span> </label>';
                            $i++;
                        }
                    }
                    echo $html;
                    ?>

                </div>
            </div>
        </div>
        <div class="med-focus-main mdMain ld-focus-main">
            <div class="med-focus-content ld-focus-content">
                <!-- <h1>Quiz 1</h1> -->
                <div class="med-quiz-wrapper learndash-wrapper" id="ajax-display-single-question">
                    <div class="wpProQuiz_content">
                        <?php if (is_user_logged_in()) { ?>
                            <?php
                            $result = display_single_question($firstItem);
                            // if($result){
                            echo $result;
                            // }else{
                            //     echo '<p>No questions tagged in this category.</p>';
                            // }
                            ?>
                        <?php
                        } else {
                            echo '<p>Please login to see this feature.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
