cd img/
mpost tietokantakaavio.mp
cp tietokantakaavio.1 tietokantakaavio.eps
mpost sidosryhmakaavio.mp
cp sidosryhmakaavio.1 sidosryhmakaavio.eps
mpost kayttoliittyma.mp
cp kayttoliittyma.1 kayttoliittyma.eps
mpost rakennekaavio.mp
cp rakennekaavio.1 rakennekaavio.eps
cd ..
pdflatex suunnitteludokumentti.tex
pdflatex toteutusdokumentti.tex
