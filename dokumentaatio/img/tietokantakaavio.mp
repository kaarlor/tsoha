input metauml;
beginfig(1);
  Class.users("users")
    ("+<<id>> id: int",
      "+username: string",
      "+password: string")();

  Class.tastings("tastings")
    ("+t_user: int",
      "+t_beer: int",
      "+t_time: timestamp")();

  Class.beers("beers")
    ("+id: int",
      "+name: string",
      "+brewery: int")();

  Class.breweries("breweries")
    ("+id: int",
      "+name: string")();

  tastings.w = users.e + (20,0);
  beers.n = tastings.s + (0,-20);
  breweries.w = beers.e + (20,0);

  drawObjects(users, tastings, beers, breweries);

%  item.aName(iAssoc)("")
%    (aName.s = 1.0[users.e, tastings.w]);
%  draw aName.n -- (aName.n + (20,0));
  link(association)(users.e -- tastings.w);
  link(association)(tastings.s -- beers.n);
  link(association)(beers.e -- breweries.w);
  

endfig;
end