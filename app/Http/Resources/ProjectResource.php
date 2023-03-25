<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $products = Product::where('project_id', $this['id'])->get();
        $records = [];
        foreach ($products as $product) {
            $record = [
                'id' => $product['id'],
                'product_type' => $product['product_type'],
                'title' => $product['title'],
                'size' => $product['size'],
                'size_name' => $product['size_name'],
                "installmentPlan" => $product['productInstallments'],
                "installmentImages" => $product['installmentImages'],
                'image_url' => env('APP_IMAGE_URL'),
                "updated_at" => $product['updated_at'],
                "created_at" => $product['created_at'],
            ];
            $records[] = $record;
        }
        return [
            "id" => $this['id'],
            "name" => $this->name,
            "location" => $this->location,
            "featured_phone_number" => $this->featured_phone_number,
            "featured_whatsapp_number" => $this->featured_whatsapp_number,
            "logo" =>  env('APP_IMAGE_URL') . 'project/' . $this->logo,
            "pdf_file" =>  env('APP_IMAGE_URL') . 'project/' . $this->pdf_file,
            "city" => $this->city->name,
            "description" => $this['description'],
            "developed_by" => $this['developed_by'],
            "developer_contact" => $this['developer_contact'],
            "approved" => $this->approved,
            "is_featured" => $this->is_featured,
            "projectImages" => $this['projectImages'],
            "products" => $records,
            "updated_at" => $this['updated_at'],
            "created_at" => $this['created_at'],
        ];
    }
}
