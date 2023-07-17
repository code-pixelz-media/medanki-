<?php
/*
Template Name: Question Tags Page Template
*/
get_header();
?>
<div class="med-row cpm-bg">
    <div class="med-tag-question">
        <ul class="med-breadcrumb">
            <li><a href="<?php echo get_home_url(); ?>">Home</a></li>
            <li><?php the_title(); ?></li>
        </ul>
        <h2 class="med-title">Oznaczone pytania</h2>
        <p class="med-para">Ucząc się, oznaczaj pytania etykietami. Stąd łatwo powtarzaj je według poziomu trudności.</p>
        <div class="med-filter-dropdown">
            <select name="Subjects" id="med-subject" class="med-drop-subject">
                <option value="all">Filtruj tematami: Wszystkie tematy</option>
            </select>
        </div>
        <div class="med-question-row">
            <?php
            if (is_user_logged_in()) {
                $taxonomy = 'question_tag';
                $terms = get_terms([
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => false,
                ]);
                if (!empty($terms)) {
                    // echo '<div class="cpm-Qtags-group">';
                    // $i = 1;
                    foreach ($terms as $term) {
                        $slug = $term->slug;
                        $name = $term->name;
                        $term_color = get_term_meta($term->term_id, 'question_term_color', true);
                        $meta_values = get_user_meta(get_current_user_id(), $slug, true);
                        $count = '0';
                        if ($meta_values) {
                            $count = count($meta_values);
                        }
                        if ($count > 1 or $count < 1) {
                            $count = 'Ilość pytań: <span>' . $count . '</span>';
                        } else {
                            $count = 'Ilość pytań: <span>' . $count . '</span>';
                        }
                        $url = get_home_url() . '/tagged-question/?tag=' . $slug;
                        // if ($slug != 'mastered') {
                        //     echo '<div class="med-question-colu" id="med-colu' . $i . '"><div class="med-icons">';
                        //     echo '<i class="fa fa-solid fa-tag med-left"></i>';
                        //     echo '<i class="fa fa-solid fa-arrow-right med-right"></i></div>';
                        //     echo '<p>once you tag questions as <span>' . $name . '</span> you can study them here.</p><h3></h3></div>';
                        // } else if ($slug != 'bookmark') {
                        //     echo '<div class="med-question-colu" id="med-colu' . $i . '"><div class="med-icons">';
                        //     echo '<i class="fa fa-solid fa-tag med-left"></i>';
                        //     echo '<i class="fa fa-solid fa-arrow-right med-right"></i></div>';
                        //     echo '<p>once you tag questions as <span>' . $name . '</span> you can study them here.</p><h3></h3></div>';
                        // } else {
                        echo '<div class="med-question-colu" id="med-colu1"><a href="' . $url . '"><div class="med-icons"><i style="background-color:' . $term_color . ';" class="fa fa-light fa-tag med-left">';
                        echo '</i><i class="fa fa-solid fa-arrow-right med-right"></i></div>';
                        echo '<p>' . $count . '</p>';
                        echo '<h3>' . $name . '</h3>  </a></div>';
                        // }
                        // $i++;
                        // if ($i > 4) {
                        //     $i = $i - 4;
                        // }
                    }
                }
            }
            ?>
        </div>
    </div>
</div>
<?php
get_footer();
