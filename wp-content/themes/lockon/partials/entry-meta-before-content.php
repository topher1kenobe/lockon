<?php
echo '<p class="document_types">Type: ';
the_terms( get_the_ID(), 'documenttype' );
echo '</p>';
