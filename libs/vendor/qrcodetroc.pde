/*
 *      qrcodetroc.pde (from PROCESSINGJS.COM HEADER ANIMATION)
 *
 *      Copyright 2011 francois@labastie.org | francois@outils-reseaux.org
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 3 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

/*
 *      PROCESSINGJS.COM HEADER ANIMATION
 *      MIT License - Hyper-Metrix.com/F1LT3R
 *      Native Processing compatible
 */

String infos; // infos texte
int count = 400; // nombre de cercles
int maxSize = 200; // tailles max de cercles
int minSize = 20; // tailles min de cercles
float[][] tabCercles; // tableau de stockage
float ds = 3; // taille du point de centre de cercle
int sel = 0; // Selection mode switch
boolean dragging=false; // Set drag switch to false

int nbrUtilisateurs;
int nbreDeLiens;
ArrayList tabListeurs;
Pulseur lePulseur;
Bouton bouton1, bouton2, bouton3;
boolean clicBtn1, clicBtn2, clicBtn3;
color cBase, cSurvol, cLigne;

/* -----------------------------------------------------------------------------------
 Méthode setup()
 ------------------------------------------------------------------------------------*/

void setup() {

  frameRate(20);
  size(screen.width, screen.height);

  // initialisation utilisateurs et liens
  nbrUtilisateurs = 0;
  nbreDeLiens = 0;
  // initialisation liste objets tabListeurs
  tabListeurs = new ArrayList();
  // pulsation
  lePulseur = new Pulseur(65,15,3);

  // Creation de font
  PFont myFont = loadFont("DejaVuSans-16");
  textFont(myFont);

  // largeur de ligne
  strokeWeight(1);
  // couleurs boutons
  cBase = color(200,50,50,180);
  cSurvol = color(0,255,0);
  cLigne = color(255);
  // boutons
  bouton1 = new Bouton(185,11,12,cBase,cSurvol,cLigne);
  bouton2 = new Bouton(210,11,12,cBase,cSurvol,cLigne);
  bouton3 = new Bouton(235,11,12,cBase,cSurvol,cLigne);

  // initialisation tabCercles
  tabCercles = new float[count][8];

  // initialisation de tableau avec valeurs aléatoires pour cercles
  for(int j=0;j< count;j++) {
    tabCercles[j][0]=random(width); // X
    tabCercles[j][1]=random(height); // Y
    tabCercles[j][2]=random(minSize, maxSize); // Radius
    tabCercles[j][3]=random(-.5,.5); // X Speed
    tabCercles[j][4]=random(-.5,.5); // Y Speed
    tabCercles[j][5]=random(255); // couleur r
    tabCercles[j][6]=random(255); // couleur g
    tabCercles[j][7]=random(255); // couleur b
  }
}

/* -----------------------------------------------------------------------------------
 Méthode draw()
 ------------------------------------------------------------------------------------*/

void draw() {

  smooth();
  background(20,30,0);
  actualiser();
  // pulsation = pulsation - 0.01;


  // +------------------------------- Début de boucle dans tableau cercles ----------------------------------+

  for (int j=0;j< nbrUtilisateurs;j++) {

    noStroke();
    // rayon et diametre
    //float radi= 50;
    float radi = dimensionner(j);
    //float radi = 100;
    float diam=radi;

    // +--------------------------------- si curseur survole le cercle --------------------------------------+
    if(( dist(tabCercles[j][0],tabCercles[j][1],mouseX,mouseY) < radi/2 )) {

      // le cercle est sélectionné
      sel = 1;

      // affiche texte
      textAlign(CENTER);
      fill(255);
      text(tabUtilisateurs[j], tabCercles[j][0],tabCercles[j][1]);

      // couleur de cercle = green
      fill(64,187,128,100);

      // si mouse down et dragging
      if(dragging) {
        // déplacement cercle à position souris
        tabCercles[j][0]=mouseX;
        tabCercles[j][1]=mouseY;
      }
    }
    // +---------------------------------------- si bouton2 cliqué ------------------------------------------+
    else if(clicBtn2 == false) {

      // animation des cercles en mode pulse désactivé
      sel = 0;

      // affiche texte
      textAlign(CENTER);
      fill(255);
      text(tabUtilisateurs[j], tabCercles[j][0],tabCercles[j][1]);

      // couleur de cercle = green
      fill(tabCercles[j][5],tabCercles[j][6],tabCercles[j][7],100);

      // si mouse down et dragging
      if(dragging) {
        // déplacement cercle à position souris
        tabCercles[j][0]=mouseX;
        tabCercles[j][1]=mouseY;
      }
    }

    // +--------------------------------------- aucun bouton cliqué ------------------------------------------+
    else {
      // couleurs aléatoires attribuées au setup
      fill(tabCercles[j][5],tabCercles[j][6],tabCercles[j][7],100);

      // animation des cercles en mode pulse désactivé
      sel = 0;
    }

    // +----------------------------------------- si bouton3 cliqué ------------------------------------------+
    if(clicBtn3 == true) {

      // animation des cercles en mode pulse activé
      sel = 1;

      // affiche texte
      textAlign(CENTER);
      fill(255);
      text(tabUtilisateurs[j], tabCercles[j][0],tabCercles[j][1]);

      // couleur de cercle = green
      fill(tabCercles[j][5],tabCercles[j][6],tabCercles[j][7],100);

      for (int m=0;m< nbrUtilisateurs;m++) {
        tabCercles[m][0]=width/2;
        tabCercles[m][1]=height/2;
      }
      clicBtn3 = false;
    }

    // +----------------------------------------- Si cercle sélectionné --------------------------------------+
    if(sel==1) {
      // affichage cercle
      // x = 75 + radi/2 * sin(3*pulsation);
      ellipse(tabCercles[j][0],tabCercles[j][1],lePulseur.activer(),lePulseur.activer());
      // couleur de centre = blanc
      fill(255,255,255,255);
      // couleur de trait = jaune
      stroke(255,255,0,100);
    }
    else {
      // affichage cercle
      ellipse(tabCercles[j][0],tabCercles[j][1],radi,radi);
      // couleur de centre = black
      fill(0,0,0,255);
      // couleur de trait = turquoise
      stroke(64,128,128,255);
    }

    // animation cercle
    tabCercles[j][0]+=tabCercles[j][3];
    tabCercles[j][1]+=tabCercles[j][4];

    // +----------------------------------------- Gestion de sorties d'écran --------------------------------+
    if( tabCercles[j][0] < 20     ) {
      tabCercles[j][3] *= -1;
    }
    if( tabCercles[j][0] > width-20 ) {
      tabCercles[j][3] *= -1;
    }
    if( tabCercles[j][1] < 20     ) {
      tabCercles[j][4] *= -1;
    }
    if( tabCercles[j][1] > height-20) {
      tabCercles[j][4] *= -1;
    }

    // +-------------------------------- Affichage point de centre de cercle --------------------------------+

    noStroke();
    rect(tabCercles[j][0]-ds,tabCercles[j][1]-ds,ds*2,ds*2);
  }

  // +------------------------------------ Affichage liens entres cercles --------------------------------+

  int nbrLiens = tabLiens.length();
  if (clicBtn1 == false) {
    for (int k=0; k< nbrLiens; k++) {
      var first = tabUtilisateurs.indexOf(tabLiens[k][0]);
      var second = tabUtilisateurs.indexOf(tabLiens[k][1]);
      noFill();
      stroke(126);
      line(tabCercles[first][0], tabCercles[first][1], tabCercles[second][0], tabCercles[second][1]);
    }
  }

  // +----------------------------------- Fin de boucle dans tableau cercles --------------------------------+



  fill(255);
  textAlign(LEFT);
  var txtuser = '';
  if (nbrUtilisateurs > 1) {
    txtuser = nbrUtilisateurs + " utilisateurs";
  } else {
    txtuser = nbrUtilisateurs + " utilisateur";
  }
  var txtlinks = '';
  if (nbreDeLiens > 1) {
    txtlinks = nbreDeLiens + " liens";
  } else {
    txtlinks = nbreDeLiens + " lien";
  }
  text(txtuser + "  |  " + txtlinks, 20, 20);

  // affichage boutons
  bouton1.survoler();
  bouton1.afficher();
  bouton2.survoler();
  bouton2.afficher();
  bouton3.survoler();
  bouton3.afficher();

}

/* -----------------------------------------------------------------------------------
 Méthode actualiser()
 ------------------------------------------------------------------------------------*/

void actualiser()
{
  // test si nbrUtilisateur vient d'etre modifié
  if (nbrUtilisateurs != tabUtilisateurs.length()) {
    nbrUtilisateurs = tabUtilisateurs.length();
  }

  // si lien ajouté
  if (nbreDeLiens != tabLiens.length()) {
    nbreDeLiens = tabLiens.length();
  }
}

/* -----------------------------------------------------------------------------------
 Méthode mouseDragged()
 ------------------------------------------------------------------------------------*/

void mouseDragged()
{
  dragging=true; // mise de drag switch à true
}

/* -----------------------------------------------------------------------------------
 Méthode mouseReleased()
 ------------------------------------------------------------------------------------*/

void mouseReleased()
{
  dragging=false; // fin de dragging
}

/* -----------------------------------------------------------------------------------
 Méthode mousePressed()
 ------------------------------------------------------------------------------------*/

void mousePressed()
{
  clicBtn1 = bouton1.clicDessus();
  clicBtn2 = bouton2.clicDessus();
  clicBtn3 = bouton3.clicSimple();
}


/* -----------------------------------------------------------------------------------
 Méthode dimensionner()
 ------------------------------------------------------------------------------------*/

int dimensionner(int num)
{
  return map(30, 0, 200, 50, 100);
}
