# Virtual Mentor

**Virtual Mentor** është një platformë mentorimi virtual e zhvilluar me PHP dhe MySQL, e realizuar si **detyrë në grup në kuadër të një projekti shkollor**, me qëllim lehtësimin e komunikimit dhe bashkëpunimit mes studentëve dhe profesorëve. Projekti synon të ofrojë një sistem të thjeshtë, funksional dhe të organizuar, duke përfshirë login dhe logout, dashboard të ndara për studentë dhe profesorë, menaxhim shënimesh, publikim njoftimesh, forum, si dhe mekanizma bazë sigurie për mbrojtjen e të dhënave.

Ky projekt është zhvilluar në mënyrë bashkëpunuese nga anëtarët e grupit, ku secili ka kontribuar në analizimin, dizajnimin, implementimin dhe testimin e funksionaliteteve të aplikacionit.

Struktura e projektit përbëhet nga skedarë PHP për logjikën e aplikacionit, HTML dhe JavaScript për ndërfaqen, si dhe një folder `database` që përmban file-n SQL me strukturën dhe të dhënat e bazës, i cili mund të përdoret për të rikrijuar bazën e të dhënave në çdo ambient lokal.

## Instalimi dhe përdorimi

Për ta përdorur projektin lokalisht, ndiqni hapat e mëposhtëm:

1. Klono repository-n nga GitHub.
2. Starto XAMPP dhe aktivizo Apache dhe MySQL.
3. Në phpMyAdmin, krijo një bazë të re dhe importo file-n `mydatabase.sql` që ndodhet në folderin `database`.
4. Kontrollo file-n `db.php` për të siguruar që kredencialet e lidhjes me bazën janë të sakta.
5. Hap projektin në browser përmes adresës:
   `http://localhost/virtualmentor`

## Funksionalitetet kryesore

* Menaxhimin e shënimeve (krijim, editim dhe fshirje)
* Publikimin dhe shikimin e njoftimeve
* Dashboard të ndara për studentë dhe profesorë
* Sistemin e login/logout
* Gjenerimin e hash-eve dhe mekanizma bazë sigurie

## Siguria

Rekomandohet që kredencialet e bazës së të dhënave të mos ruhen publikisht për arsye sigurie.
