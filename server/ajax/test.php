<?php
	$link = mysqli_connect( 
            'localhost',  /* Хост, к которому мы подключаемся */ 
            'foggy_razin',       /* Имя пользователя */ 
            '666666',   /* Используемый пароль */ 
            'foggy_razin');     /* База данных для запросов по умолчанию */ 
	if (!$link) { 
	   printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error()); 
	   exit; 
	} 
	if ($result = mysqli_query($link, 'SELECT * FROM pr_codes LIMIT 100')) { 

    /* Выборка результатов запроса */ 
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