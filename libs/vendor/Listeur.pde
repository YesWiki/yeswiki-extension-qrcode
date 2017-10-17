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

/*
 Classe Listeur
 */

public class Listeur {

  String nom;
  ArrayList listeAmis;
  int nbrAmis;

  /* ------------------------
   constructeur
   --------------------------*/

  public Listeur(String leNom) {
    this.nom = leNom;
    listeAmis = new ArrayList();
  }

  /* ------------------------
   Méthode getNom()
   --------------------------*/

  String getNom() {
    return this.nom;
  }

  /* ------------------------
   Méthode setListeAmis()
   --------------------------*/
  void setListeAmis(String nouvelAmi) {
    this.listeAmis.add(nouvelAmi)
    }

    /* ------------------------
     Méthode estAmiAvec()
     --------------------------*/

    boolean estAmiAvec(String unMail) {
      //for (int i=0; i<this.listeAmis.size(); i++) {
        //String unAmi = (String) listeAmis.get(i);
        //if(unMail.equals(unAmi)) {
        //  return true;
        //}
      //}
      return false;
    }

  /* ------------------------
   Méthode getNbrAmis()
   --------------------------*/

  int getNbrAmis() {
    return this.listeAmis.size();
  }

  /* ------------------------
   Méthode afficheAmis()
   --------------------------*/
  void afficheAmis() {
    for(int i=0; i<this.listeAmis.size(); i++) {
      println("ami = "+(String) listeAmis.get(i));
    }
  }
}
