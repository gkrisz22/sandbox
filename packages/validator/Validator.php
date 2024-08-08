<?php

/**
 * Zod-inspirált validátor osztály: kapott adatok séma alapú ellenőrzése
 * @version 1.0
 * @since 2024-08-07
 * @author Géczy Krisztián
 */

class Validator {

    /**
     * @var array $schema - Várt mezők és azok validációs szabályai
    */
    private array $schema;

    /**
     * @var array $errors - Validáció közbeni hibák
    */
    private array $errors;

    /**
     * @var array $BUILT_IN_TYPES - Beépített típusok (PHP 8-nál szereplő típusok)
    */
    private const BUILT_IN_TYPES = ['string', 'integer', 'boolean', 'double', 'float', 'resource', 'NULL', 'array', 'object'];

    public function __construct(array $schema) {
        $this->schema = $schema;
        $this->errors = [];
    }

    /**
     * Statikus metódus, amely létrehoz egy új példányt a kapott adatok és séma alapján
     * @param array $data   - Validálandó adatok
     * @param array $schema - Validációs séma
     * @return Validator    - Az új példány
     */
    public static function make($data, $schema) {
        $validator = new Validator($schema);
        $validator->validate($data);
        return $validator;
    }

    /**
     * A kapott adatokat megtisztítja a HTML speciális karakterektől
     * @param array $data   - Tisztítandó adatok
     * @return array        - Tisztított adatok
     */
    public static function sanitize_array($data) {

        $sanitized = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitized[$key] = self::sanitize_array($value);
            } elseif (is_string($value)) {
                $sanitized[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }

    /**
     * Validálja a kapott adatokat a séma alapján
     * @param array $data   - Ellenőrizendő adatok
     * @return bool         - Sikeres volt-e a validáció
     */
    public function validate(array $data) : bool {

        $this->errors = [];

        foreach ($this->schema as $field => $rules) {
            $value = isset($data[$field]) ? $data[$field] : null;
            $this->validateField($field, $value, $rules);
        }

        return empty($this->errors);

    }

    /**
     * Visszaadja a validáció közbeni hibákat
     * @return array - Hibák
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Egy mező validálása
     * @param string $field - Mező neve
     * @param mixed $value  - Mező értéke
     * @param array $rules  - Mező validációs szabályai
     */
    private function validateField($field, $value, $rules)
    {
        if (!$rules['required'] && is_null($value)) {
            return;
        }

        foreach ($rules as $rule => $rv) {
            switch ($rule) {
                case 'required':
                    if ($rv && is_null($value)) {
                        $this->errors[$field][] = 'Kötelezően kitöltendő mező.';
                        return;
                    }
                    break;
                case 'type':
                    if(!in_array($rv, self::BUILT_IN_TYPES)) {
                        $this->errors[$field][] = 'Nem támogatott típus: ' . $rv;
                        return;
                    }
                    if (!is_null($value) && gettype($value) !== $rv) {
                        $this->errors[$field][] = 'A mező ' . $rv . ' típusú kell legyen.';
                    }
                    break;
                case 'min':
                    if (!is_null($value)) {
                        if (is_string($value) && strlen($value) < $rv) {
                            $this->errors[$field][] = 'A mező értéke legalább ' . $rv . ' karakter hosszú kell legyen.';
                        } elseif (is_numeric($value) && $value < $rv) {
                            $this->errors[$field][] = 'A mező értéke legalább ' . $rv . ' kell legyen.';
                        }
                    }
                    break;
                case 'max':
                    if (!is_null($value)) {
                        if (is_string($value) && strlen($value) > $rv) {
                            $this->errors[$field][] = 'A mező értéke legfeljebb ' . $rv . ' karakter hosszú lehet.';
                        } elseif (is_numeric($value) && $value > $rv) {
                            $this->errors[$field][] = 'A mező értéke legfeljebb ' . $rv . ' lehet.';
                        }
                    }
                    break;
                case 'regex':
                    if (!is_null($value) && !preg_match($rv, $value)) {
                        $this->errors[$field][] = 'Az érték formátuma nem felel meg a követelményeknek.';
                    }
                    break;
                case 'is_email': 
                    if (!is_null($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $this->errors[$field][] = 'A megadott email cím nem megfelelő formátumú.';
                    }
                    break;
                case 'must_contain':
                    if (!is_null($value) && !str_contains($value, $rv)) {
                        $this->errors[$field][] = 'A mezőnek tartalmaznia kell a(z) ' . $rv . ' karaktert.';
                    }
                    break;
                default:
                    $this->errors[$field][] = 'Nem definiált validációs szabály: ' . $rule;
            }
        }
    }

}

// TESZT

$teszt_adatok = [
    'szoveg' => 'valami',
    'szam' => 42
];
$schema = [
    'szoveg' => [
        'required' => true,
        'type' => 'string',
        'min' => 5,
        'max' => 10,
    ],
    'szam' => [
        'required' => true,
        'type' => 'integer',
        'min' => 5,
        'max' => 100
    ]
];
$validator = Validator::make(Validator::sanitize_array($teszt_adatok), $schema);

if($validator->getErrors()) {
    print_r($validator->getErrors());
} else {
    echo "Nincs hiba!";
}