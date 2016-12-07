<?php

function post_type_agenda() {
    $labels = array(
        'name' => 'Agenda',
        'singular_name' => 'Agenda',
        'menu_name' => 'Agenda',
        'name_admin_bar' => 'Agenda',
        'add_new' => 'Tambah Agenda',
        'add_new_item' => 'Tambah Agenda Baru',
        'new_item' => 'Agenda Baru',
        'edit_item' => 'Edit Agenda',
        'view_item' => 'Lihat Agenda',
        'all_items' => 'Semua Agenda',
        'search_items' => 'Cari Agenda',
        'parent_item_colon' => 'Agenda Induk:',
        'not_found' => 'Tidak Ada Agenda Ditemukan',
        'not_found_in_trash' => 'Agenda tidak ditemukan di trash'
    );

    $args = array(
        'public' => true,
        'labels' => $labels,
        'description' => 'Manejemen agenda dan event.',
        'menu_icon' => 'dashicons-calendar-alt',
        'taxonomies' => array('tag_agenda', 'kategori_agenda')
    );
    register_post_type('agenda', $args);
}

add_action('init', 'post_type_agenda');

function kategori_agenda() {
    $labels = array(
        'name' => _x('Kategori Agenda', 'taxonomy general name'),
        'singular_name' => _x('Kategori Agenda', 'taxonomy singular name'),
        'search_items' => __('Cari Kategori Agenda'),
        'all_items' => __('Semua Kategori Agenda'),
        'parent_item' => __('Kategori Agenda Induk'),
        'parent_item_colon' => __('Kategori Agenda Induk:'),
        'edit_item' => __('Edit Kategori Agenda'),
        'update_item' => __('Update Kategori Agenda'),
        'add_new_item' => __('Tambah Kategori Agenda'),
        'new_item_name' => __('Kategori Agenda Baru'),
        'menu_name' => __('Kategori Agenda'),
    );
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
    );
    register_taxonomy('kategori_agenda', 'agenda', $args);
}

add_action('init', 'kategori_agenda', 0);

add_action('init', 'tag_agenda', 0);

//create two taxonomies, genres and tags for the post type "tag"
function tag_agenda() {
    // Add new taxonomy, NOT hierarchical (like tags)
    $labels = array(
        'name' => _x('Tag', 'taxonomy general name'),
        'singular_name' => _x('Tag', 'taxonomy singular name'),
        'search_items' => __('Cari Tag'),
        'popular_items' => __('Tag Populer'),
        'all_items' => __('Semua Tag'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit Tag'),
        'update_item' => __('Update Tag'),
        'add_new_item' => __('Tambah Tag Baru'),
        'new_item_name' => __('New Tag Name'),
        'separate_items_with_commas' => __('Pisahkan tag dengan koma.'),
        'add_or_remove_items' => __('Tambah atau hapus tag'),
        'choose_from_most_used' => __('Pilih dari tag populer'),
        'menu_name' => __('Tags'),
    );

    register_taxonomy('tag_agenda', 'agenda', array(
        'hierarchical' => false,
        'labels' => $labels,
        'show_ui' => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var' => true,
        'rewrite' => array('slug' => 'tag-agenda'),
    ));
}
