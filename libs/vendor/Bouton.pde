/*
 *      qrcodetroc.pde
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

/* -----------------------------------------
 Classe Bouton
 -------------------------------------------- */

public class Bouton {

  int x, y;
  int taille;
  boolean leSwitch;
  color couleurBase;
  color couleurDessus;
  color couleurLigne;
  boolean survol;  
  boolean clic; 

  /* ------------------
   * constructeur
   * ----------------- */

  Bouton(int unX, int unY, int uneTaille, color uneCouleurBase, color uneCouleurDessus, color uneCouleurLigne) {
    this.x = unX;
    this.y = unY;
    this.taille = uneTaille;
    this.leSwitch = false;
    survol = false;  
    clic = false; 
    this.couleurBase = uneCouleurBase;
    this.couleurDessus = uneCouleurDessus;
    this.couleurLigne = uneCouleurLigne;
  }

  /* ------------------
   * methode afficher
   * ----------------- */

  void afficher() {
    noStroke();
    //stroke(couleurLigne);
    rect(x, y, taille, taille);
    /*
    if (survol) {
     fill(couleurDessus);
     }
     else {
     fill(couleurBase);
     }
     */
  }

  /* --------------------
   * methode survoler
   * ----------------- */

  boolean survoler()   
  {  
    if(mouseX >= x && mouseX <= x+taille &&   
      mouseY >= y && mouseY <= y+taille) {  
      fill(couleurDessus);
      survol = true;  
      return true;
    }   
    else {  
      fill(couleurBase);
      survol = false;  
      return false;
    }
  } 

  /* ------------------
   * methode clicDessus
   * ----------------- */

  boolean clicDessus() {
    if (mousePressed == true && survoler()) {
      leSwitch = !leSwitch;
      return leSwitch;
    }
    return leSwitch;
  }

  /* ------------------
   * methode clicSimple
   * ----------------- */

  boolean clicSimple() {
    if (mousePressed == true && survoler()) {
      return true;
    }
    return false;
  }
}

