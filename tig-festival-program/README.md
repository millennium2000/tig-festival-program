# TIG Festival Program

WordPress plugin fesztivÃ¡l Ã©s csalÃ¡di nap program megjelenÃ­tÃ©sÃ©hez, szerkesztÅi admin felÃ¼lettel.

## HasznÃ¡lat

1. MÃ¡sold a `tig-festival-program` mappÃ¡t a WordPress `wp-content/plugins/` kÃ¶nyvtÃ¡rÃ¡ba.
2. AktivÃ¡ld a bÅvÃ­tmÃ©nyt a WordPress admin felÃ¼leten.
3. A bal oldali admin menÃ¼ben nyisd meg a `DG Program` oldalt.
4. Add meg, mÃ³dosÃ­tsd vagy tÃ¶rÃ¶ld a helyszÃ­neket. Ezek lesznek a tÃ¡blÃ¡zat oszlopai az `IdÅpont` utÃ¡n.
5. Add meg, mÃ³dosÃ­tsd vagy tÃ¶rÃ¶ld az idÅpontokat Ã©s az idÅpontokhoz tartozÃ³ helyszÃ­nes programokat.
6. A tag-eket kÃ¼lÃ¶n szerkesztheted: bÅvÃ­thetÅk, Ã¡tÃ­rhatÃ³k Ã©s tÃ¶rÃ¶lhetÅk.
7. A programoknÃ¡l a tag opcionÃ¡lis, vÃ¡laszthatÃ³ a `Nincs tag` Ã©rtÃ©k is.
8. Ha egy program tÃ¶bb idÅsÃ¡von Ã¡t tart, add meg a `VÃ©ge` idÅpontot. Desktop nÃ©zetben a cella Ã¶sszevonva jelenik meg.
9. Illeszd be a shortcode-ot egy oldalba vagy bejegyzÃ©sbe:

```text
[dg_program]
```

## FÃ¡jlok

- `tig-festival-program.php` - plugin belÃ©pÃ©si pont Ã©s shortcode
- `templates/program.php` - adatvezÃ©relt program HTML template
- `assets/css/tig-festival-program.css` - megjelenÃ©s
- `assets/js/tig-festival-program.js` - tooltip fÃ³kuszkezelÃ©s
