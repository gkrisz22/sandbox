Ahogyan reggel említettem, próbáltam keresni biztonságosabb eljárásokat ahhoz, hogy biztonságosabbá és skálázhatóbbá váljon az ELME rendszere.
A megoldás, amit elkezdtem készíteni lényegében egy Laravelhez hasonló API rendszer, de mégsem olyan szigorú. Sőt, könnyen bővíthető és testreszabható.
Mellékelek egy ábrát, hogy hogyan is működne a rendszer.

Adott egy $.ajax kérés, amiben egy tetszőleges JSON objektumot szeretnénk elküldeni, amit feldolgoznánk. Ezt az új architektúrában AES-256-CBC algoritmussal titkosítva küldenénk el a szervernek - ami még kvantumszámítógéppel sem törhető- , úgy, hogy a kulcs megegyezik az adat titkosításánál, illetve a szerveren. Ez egy manuálisan beállított kulcs.

A $.ajax kérést kezdeményező fájlban először megadhatunk egy JSON objektumot, mint titkos adat, amit elküldünk a szervernek. Ezt titkosítja az AES-256-CBC algoritmus egy megadott kulccsal. 
Az új végpontokat fogadó állományok middleware-ekkel van kibővítve. Képes több middleware-t is lefuttatni szekvenciálisan a kérés feldolgozása előtt. A middlwarenek meg kell adni azt a kulcsot, amivel titkosítva lett az adat, így tetszőleges függvény megadható, hogy hogyan dolgozza fel az adatot.

