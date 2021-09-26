<?php

    class News
    {
        /**
         *  Возращает новость по id
         */
        public static function getNewsItemById($id)
        {
             // Запрос к бд
            $id = intval($id);
            if($id){
                $db = DB::getConnection();
                $result = $db->query('SELECT * from news WHERE id=' . $id);
                $result->setFetchMode(PDO::FETCH_ASSOC);
                return $result->fetch();
            }
        }

        /**
         * Возвращает массив новостей
         */
        public static function getNewsList()
        {
            $db = DB::getConnection();

            // Получаем данные из БД
            $newsList = array();
            $result = $db->query('SELECT id, title, date, content '
                . 'FROM news '
                .  'ORDER BY date DESC '
                . 'LIMIT 10'
            );

            // Из строки запроса получаем данные
            $i = 0;
            while($row = $result->fetch()){
                $newsList[$i]['id'] = $row['id'];
                $newsList[$i]['title'] = $row['title'];
                $newsList[$i]['date'] = $row['date'];
                $newsList[$i]['content'] = $row['content'];
                $i++;
            }
            return $newsList;
        }
    }