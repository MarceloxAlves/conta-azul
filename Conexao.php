<?php
require_once 'constantes.php';

/**
 * Classe abstrata  de conexao. Padrão SingleTon
 * Retorna o um objeto pdo pelo getCon()
 *
 * @author Marcelo Alves
 */
class Conexao
{

    private static $Host = SERVER;
    private static $User = USER;
    private static $Pass = PASSWORD;
    private static $Dbsa = DATABASE;
    private static $Type = 'mysql';

    /** @var PDO */
    private static $Connect = null;

    private static function Conectar()
    {
        try {
            /**
             *  se a não existe conexao SINGLETON
             */
            if (self::$Connect == null):
                switch (self::$Type):
                    case 'mysql':
                        $dsn = 'mysql:host=' . self::$Host . ';dbname=' . self::$Dbsa;
                        break;
                    default :
                        $dsn = 'mysql:host=' . self::$Host . ';dbname=' . self::$Dbsa;

                endswitch;

                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                self::$Connect = new PDO($dsn, self::$User, self::$Pass, $options);

            endif;
        } catch (PDOException $e) {
            var_dump($e->getCode(), $e->getMessage(), $e->getFile(), $e->getFile());
            die;
        }

        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }

    /**
     *
     * @return PDO SingleTon  Pattern
     */
    public static function getConn()
    {
        return self::Conectar();
    }

    public static function readSQL($select, $parseString = null, $conn = null)
    {
        try {
            if (!$conn) {
                $conn = self::getConn();
            }

            $places = array();

            if (!empty($parseString)):
                parse_str($parseString, $places);
            endif;

            $read = $conn->prepare($select);
            $read->setFetchMode(PDO::FETCH_ASSOC);

            if (count($places)):
                foreach ($places as $key => $value):
                    if ($key == 'limit' || $key == 'offset'):
                        $value = (int)$value;
                    endif;
                    $read->bindValue($key, $value, (is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR));
                endforeach;
            endif;

            $read->execute();
            return $read->fetchAll();
        } catch (PDOException $exception) {
            var_dump($exception->getMessage());
            return [];
        }

    }

    public static function update($table, $dados, $termos, $parseString, $conn = null)
    {
        try {
            if (!$conn) {
                $conn = self::getConn();
            }
            $places = null;
            parse_str($parseString, $places);

            foreach ($dados as $key => $value):
                $places_data[] = $key . '= :' . $key;
            endforeach;
            $places_data = implode(',', $places_data);

            $update = $conn->prepare("UPDATE {$table} SET {$places_data} {$termos}");

            $update->execute(array_merge($dados, $places));
            return true;
        } catch (PDOException $exception) {
            var_dump($exception->getMessage());
            return false;
        }

    }


}
