input metauml;
input TEX;

beginfig(1);

  Actor.rton_kayttaja("Rekisteröitymätön", "käyttäjä");
  Actor.rnyt_kayttaja("Rekisteröitynyt", "käyttäjä");
  Usecase.rekisteroityminen("Käyttäjätilin", "luonti");

  Usecase.oluen_lisaaminen("Oluen lisääminen");
  Usecase.oluiden_selaaminen("Oluiden selaaminen");
  Usecase.kirjautuminen("Kirjautuminen");

  rton_kayttaja.e = rekisteroityminen.w + (-40,0);
  rton_kayttaja.s = rnyt_kayttaja.n + (0,10);
  rnyt_kayttaja.e = oluen_lisaaminen.w + (-40,0);
  kirjautuminen.w = oluen_lisaaminen.e + (60,0);
  oluiden_selaaminen.n = oluen_lisaaminen.s + (0,-10);
  
  drawObjects(rton_kayttaja, rnyt_kayttaja, rekisteroityminen, oluen_lisaaminen, kirjautuminen, oluiden_selaaminen);

  link(association)(rton_kayttaja.e -- rekisteroityminen.w);
  link(association)(rnyt_kayttaja.e -- oluen_lisaaminen.w);
  link(dependency)(oluen_lisaaminen.e -- kirjautuminen.w);
  link(association)(rnyt_kayttaja.e -- oluiden_selaaminen.w);
  link(dependency)(oluiden_selaaminen.e -- kirjautuminen.sw);

  item(iStereo)("<<include>>")(obj.s = .5[oluen_lisaaminen.e, kirjautuminen.w]);
  item(iStereo)("<<include>>")(obj.n = .5[oluiden_selaaminen.e, kirjautuminen.sw]);
    
  
endfig;
end