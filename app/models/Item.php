<?php 


class Item extends Eloquent {

	protected $fillable = ['author_id','collection_id','name','description','url','method','header','data'];
	protected $table = 'collection';

}
