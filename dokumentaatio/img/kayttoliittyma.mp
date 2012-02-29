input metauml;
beginfig(1);
  Class.aloitus("aloitussivu")("julkinen")();
  Class.rekist("rekisteroitymissivu")("julkinen")();
  Class.userhome("kayttajasivu")()();
  Class.hallinto("hallintasivu")("-vain hallintakayttajalle")();
  
  aloitus.e = rekist.w + (-20,0);
  userhome.n = aloitus.s + (0,-40);
  hallinto.w = userhome.e + (20,0);

  drawObjects(aloitus, rekist, userhome, hallinto);

%  item.aName(iAssoc)("")
%    (aName.s = 1.0[users.e, tastings.w]);
%  draw aName.n -- (aName.n + (20,0));
  link(association)(aloitus.e -- rekist.w);
  link(association)(userhome.n -- aloitus.s);
  link(association)(userhome.e -- hallinto.w);

endfig;
end