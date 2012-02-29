input metauml;
input TEX;

beginfig(1);

  Actor.rton_kayttaja("Rekisteroitymaton", "kayttaja");
  Actor.rnyt_kayttaja("Rekisteroitynyt", "kayttaja");
  Actor.hallintakayttaja("Hallinta-", "kayttaja");
  
  Usecase.rekisteroityminen("Kayttajatilin", "luonti");

  Usecase.oluen_lisaaminen("Oluen lisaaminen");
  Usecase.oluiden_selaaminen("Oluiden selaaminen");
  Usecase.kirjautuminen("Kirjautuminen");

  Usecase.hallinnointi("Tietokannan hallinnointi");

  rton_kayttaja.e = rekisteroityminen.w + (-40,0);
  rton_kayttaja.s = rnyt_kayttaja.n + (0,10);
  rnyt_kayttaja.s = hallintakayttaja.n + (0,30);
  oluiden_selaaminen.s = hallinnointi.n + (0,15);
  rnyt_kayttaja.e = oluen_lisaaminen.w + (-40,0);
  kirjautuminen.w = oluen_lisaaminen.e + (60,0);
  oluiden_selaaminen.n = oluen_lisaaminen.s + (0,-10);
  
  drawObjects(rton_kayttaja, rnyt_kayttaja, hallintakayttaja, rekisteroityminen, oluen_lisaaminen, kirjautuminen, oluiden_selaaminen, hallinnointi);

  link(association)(rton_kayttaja.e -- rekisteroityminen.w);
  link(association)(rnyt_kayttaja.e -- oluen_lisaaminen.w);
  link(inheritance)(hallintakayttaja.n -- rnyt_kayttaja.s);
  link(dependency)(oluen_lisaaminen.e -- kirjautuminen.w);
  link(dependency)(hallinnointi.e -- kirjautuminen.s);
  link(association)(rnyt_kayttaja.e -- oluiden_selaaminen.w);
  link(association)(hallintakayttaja.e -- hallinnointi.w);
  link(dependency)(oluiden_selaaminen.e -- kirjautuminen.sw);

  item(iStereo)("<<extends>>")(obj.n = .5[hallintakayttaja.n, rnyt_kayttaja.s]);
  item(iStereo)("<<include>>")(obj.s = .5[oluen_lisaaminen.e, kirjautuminen.w]);
  item(iStereo)("<<include>>")(obj.n = .5[oluiden_selaaminen.e, kirjautuminen.sw]);
  item(iStereo)("<<include>>")(obj.n = .5[hallinnointi.e, kirjautuminen.sw]);
    
endfig;
end