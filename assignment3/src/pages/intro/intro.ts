import { Component } from '@angular/core';
import { IonicPage, NavController, NavParams } from 'ionic-angular';
import { HomePage } from '../home/home';
import { ClientPage } from '../client/client';
import { Geolocation } from '@ionic-native/geolocation';
import { Storage } from '@ionic/storage';
import {Http,Headers} from '@angular/http';
/**
 * Generated class for the IntroPage page.
 *
 * See https://ionicframework.com/docs/components/#navigation for more info on
 * Ionic pages and navigation.
 */

@IonicPage()
@Component({
  selector: 'page-intro',
  templateUrl: 'intro.html',
})
export class IntroPage {
  lat;
  long;
  constructor(public navCtrl: NavController, public navParams: NavParams,private geolocation: Geolocation,private storage: Storage,private http:Http) {
    this.geolocation.getCurrentPosition().then((resp) => {
      //console.log('lat');
      //console.log('long');
      this.lat=resp.coords.latitude;
      this.long=resp.coords.longitude;
     }).catch((error) => {
       console.log('Error getting location', error);
     });
 
  }

  ionViewDidLoad() {
    console.log('ionViewDidLoad IntroPage');
  }
  opendriver(){
    this.navCtrl.push(HomePage);
  }
  openclient(){
    this.navCtrl.push(ClientPage);
  }


}
