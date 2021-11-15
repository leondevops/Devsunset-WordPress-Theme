<?php
/*
 * Pre define several helper method to handle custom post type.
 *
 * */

	/**
	 * Retrieve the term with embedded hyperlinks
	 *
	 * @param int    $postID          // The ID of current post
	 * @param string $postTaxonomy    // The taxonomy of related post
	 *
	 * @return string $output         // Return a post meta with embedded hyperlink
	 * */
	function get_terms_with_hyperlink($postID, $postTaxonomy = 'post'){
		// 1. Validate the input post Id
		if ( (get_post_status($postID) == false) ) {
			return '';
		}

		// 2. Validate the input taxonomy (even though giving default result)
		global $wp_taxonomies;

		if( !isset($wp_taxonomies[ $postTaxonomy ]) ){
			return '';
		}

		$postTerms = wp_get_post_terms($postID, $postTaxonomy);
		$output = '';

		$postCounter = 0;
		foreach ($postTerms as $postTerm):
			$postCounter++;

			if($postCounter > 1) {
				$output .= ', ';
			}

			$output .= '<a href="'.get_term_link($postTerm).'">'.$postTerm->name.'</a>';

		endforeach;

		return $output;
	}

?>