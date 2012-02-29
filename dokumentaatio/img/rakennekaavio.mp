input metauml;
beginfig(1);
  Class.aloitus("aloitussivu") ("(index.php)")();
  
  Activity.koikeus("Kayttooikeus");
  koikeus.n = aloitus.s + (0,-30);

  Class.rekist("rekisteroitymissivu")("(new_user.php)")();
  aloitus.sw = rekist.ne + (40,30);

  Activity.actrekist("Rekisteroityminen");
  actrekist.n = rekist.s + (0,-30);

  Class.userhome("kayttajasivu")("(user_home.php)")();
  userhome.n = koikeus.s + (0,-30);

  Activity.addbeer("Lisaa olut");
  addbeer.w = userhome.e + (40,0);
  
  Activity.hoikeus("Hallintaoikeus");
  hoikeus.n = userhome.s + (0,-30);

  Class.hallinto("hallintasivu")("vain hallintakayttajalle")();
  hallinto.n = hoikeus.s + (0,-30);
  
  drawObjects(aloitus, koikeus, rekist, actrekist, userhome, addbeer, hoikeus, hallinto);

%  item.aName(iAssoc)("")
%    (aName.s = 1.0[users.e, tastings.w]);
%  draw aName.n -- (aName.n + (20,0));

  link(associationUni)(aloitus.s -- koikeus.n);
  item(iStereo)("submit")(obj.s = .5[aloitus.s, koikeus.n]);
  
  link(associationUni)(aloitus.sw -- rekist.ne);
  item(iStereo)("link")(obj.s = .5[aloitus.sw, rekist.ne]);

  link(associationUni)(rekist.s -- actrekist.n);
  item(iStereo)("submit")(obj.s = .5[rekist.s, actrekist.n]);

  link(associationUni)(actrekist.ne -- aloitus.sw);
  
  link(associationUni)(koikeus.s -- userhome.n);

  link(associationUni)(userhome.e -- addbeer.w);
  item(iStereo)("submit")(obj.s = .5[userhome.e, addbeer.w]);

  link(associationUni)(userhome.s -- hoikeus.n);
  item(iStereo)("link")(obj.s = .5[userhome.s, hoikeus.n]);  
  link(associationUni)(hoikeus.s -- hallinto.n);

endfig;
end