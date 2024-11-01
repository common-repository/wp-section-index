<?php
/****************************************
Section Name: Array of Fields
****************************************/

$matty_prefix 			= 'wpsi_';


$matty_field_array = array(
					array(
						'field' => 'tag', 
						'message' => 'Please select the heading type you would like to use to separate content into a section index.', 
						'type' => 'text', 
						'required' => 1
						), 
					array(
						'field' => 'use_pages', 
						'message' => 'Please select whether or not you would like the section index to be created on Pages.', 
						'type' => 'text', 
						'required' => 1
					), 
					array(
						'field' => 'use_posts', 
						'message' => 'Please select whether or not you would like the section index to be created on Posts.', 
						'type' => 'text', 
						'required' => 1
						), 
					array(
						'field' => 'use_backtotop', 
						'message' => 'Please select whether or not you would like to insert "Back to top" links in Posts or Pages which use the section index.', 
						'type' => 'text', 
						'required' => 1
						), 
					array(
						'field' => 'backtotop_id', 
						'message' => 'Please specify the ID of the element to which the "Back to top" links are to point.', 
						'type' => 'text', 
						'required' => 0
						), 
					array(
						'field' => 'display_all_anchors', 
						'message' => 'Please select whether you would like to display all the section indexes in a paginated page or post, or just those for the page currently being viewed.', 
						'type' => 'text', 
						'required' => 1
						)
					/*
					, array(
						'field' => 'clean_data', 
						'message' => 'Please select whether or not you would like the options set on this screen to be removed when the plugin is deactivated.', 
						'type' => 'text', 
						'required' => 1
						)
					*/
					);

/****************************************
Section Name: Form Processing
****************************************/

if (isset($_POST['wpsi_update'])) {

$error_array 			= array();
$error_array 			= NULL;
													
	/*---Create Empty Variables----------*/
	
	foreach ($matty_field_array as $field) {
		${'v_' . $matty_prefix . $field['field']} = ''; echo "\n";
	}
	
	/*---Create POST variables-----------*/
	
	foreach ($matty_field_array as $field) {	
		${'v_' . $matty_prefix . $field['field']} = trim(strip_tags($_POST[$matty_prefix . $field['field']])); echo "\n";
	}
	
	/*---Form Validation-----------------*/
	
	foreach ($matty_field_array as $field) {
		
		if ($field['required'] == 1) {
		
			if (!isset($_POST[$matty_prefix . $field['field']]) || ${'v_' . $matty_prefix . $field['field']} == '') {
			
				$error_array[] = $field['message'];
			
			} else if ($field['type'] == 'email' && !is_email(${'v_' . $matty_prefix . $field['field']})) {
		
				$error_array[] = 'The email address provided is invalid. Please re-enter your email address.';
		
			}
		
		} // End IF Statement
	}
	
	/*---Error Reports & Data Insertion--*/
	
	if ($error_array == NULL) {
	
		$matty_options = array();
		
		foreach ($matty_field_array as $field) {
			
			if ($field['type'] == 'nav') {
			
				$pages_array = $_POST[$matty_prefix . $field['field']];
				$pages_csv = '';
				for ($i = 0; $i < count($pages_array); $i++)
				{
					$pages_csv .= $pages_array[$i]; 
					if ($i < count($pages_array)-1 && $i < count($pages_array)) {
						$pages_csv .= ',';
					} // End IF Statement
			
				} // End FOR Loop
			
				$matty_options[] = array($matty_prefix . $field['field'], $pages_csv);
			} else {
				$matty_options[] = array($matty_prefix . $field['field'], ${'v_' . $matty_prefix . $field['field']});
			}
			
		} // End FOREACH
		
		foreach ($matty_options as $option) {
			update_option($option[0], $option[1]);
		}
		
		echo "<div id=\"message\" class=\"updated fade\"><p><strong>" . esc_html( $title ) . " settings have been updated successfully.</strong></p></div>";
	
	} else {
		echo '<div id="message" class="error"><p><strong>The following errors have occurred:</strong><br /><ul>';
		for($i = 0; $i < count($error_array); $i++) {
		
			echo '<li>' . $error_array[$i] . '</li>';
		
		}
		echo '</ul></p></div>';
	
	} // End IF Statement

} // End $_POST IF Statement

/*---Variables For Form--------------*/

foreach ($matty_field_array as $field) {
	
	if (isset($_POST[$matty_prefix . $field['field']])) {
	
		${'v_' . $matty_prefix . $field['field']} = $_POST[$matty_prefix . $field['field']];
	
	} else {

		${'v_' . $matty_prefix . $field['field']} = get_option($matty_prefix . $field['field']);
		
	}

} // End FOREACH
	
/****************************************/

/****************************************
Section Name: Database Clean-up
****************************************/

if ( isset($_POST['wpsi_databasecleanup']) ) {
	
	if ( isset($_POST['wpsi_databasecleanup_tnc']) &&  $_POST['wpsi_databasecleanup_tnc'] == 'y' ) {
	
		global $wpsi;
		
		$removed_options = array();
		$failed_options = array();
		$html = '';
		
		print_r($wpsi_options);
		
		foreach ( $wpsi->options as $key => $value ) {
			
			$removed = delete_option ( $wpsi->prefix . $key );
			
			if ( $removed == true ) {
				$removed_options[] = $wpsi->prefix . $key;	
			} else {
				$failed_options[] = $wpsi->prefix . $key;	
			} // End IF Statement
			
		} // End FOREACH Loop
		
		if ( count ($failed_options ) > 0 ) {
			
			$html .= '<div class="error fade"><p>The following options were not removed from the database:</p><ul>';
				foreach ( $failed_options as $o ) {
					
					$html .= '<li><code>' . $o . '</code></li>' . "\n";
					
				} // End FOREACH Loop
			$html .= '</ul></div>';
				
		} else {
		
			$html .= '<div class="updated fade"><p>The following options were successfully removed from the database:</p><ul>';
				foreach ( $removed_options as $o ) {
					
					$html .= '<li><code>' . $o . '</code></li>' . "\n";
					
				} // End FOREACH Loop
			$html .= '</ul><p>(Please reload this screen to view the reset options form.)</p></div>';
			
		} // End IF Statement
		
		echo $html;
		
	} else {
		
		echo '<div class="error fade"><p>Please be sure to check the checkbox, acknowledging that you understand what you are doing when clicking the "Clean Section Index Database &rarr;" button.</p></div>';
	
	} // End IF Statement
		
} // End IF Statement

?>