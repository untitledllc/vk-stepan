<?php
	$link = mysqli_connect( 
            'localhost',  /* ����, � �������� �� ������������ */ 
            'foggy_razin',       /* ��� ������������ */ 
            '666666',   /* ������������ ������ */ 
            'foggy_razin');     /* ���� ������ ��� �������� �� ��������� */ 
	if (!$link) { 
	   printf("���������� ������������ � ���� ������. ��� ������: %s\n", mysqli_connect_error()); 
	   exit; 
	} 
	if ($result = mysqli_query($link, 'SELECT * FROM pr_codes LIMIT 100')) { 

    /* ������� ����������� ������� */ 
	echo '<ul class="recentpost">';
    while($row = mysqli_fetch_assoc($result)) { 
        if ($row['status']==0) {
			printf("<li>%s</li>\n", $row['code']);
		}
    }
	echo '</ul>';
    mysqli_free_result($result); 
	mysqli_close($link); 
}
?>