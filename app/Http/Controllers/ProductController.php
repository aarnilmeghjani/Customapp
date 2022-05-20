<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function index(Request $request)
    {

            $user = User::where('name', 'demo-aarnil-meghjani.myshopify.com')->first();

        $next = $request->next;
        $previous = $request->previous;
        $query =
            'query Products($cursor:String, $beforeLink:String, $next:Int, $last:Int){
                products(first: $next,after:$cursor,last:$last,before:$beforeLink) {
                    edges {
                        node {
                            legacyResourceId
                            description
                            title
                            status
                            tags
                            vendor
                        }
                    }
                     pageInfo {
                          hasNextPage
                          hasPreviousPage
                          startCursor
                          endCursor
                    }
                }
            }';
        $variables = [
            "cursor" => null,
            "last" => null,
            "next" => 3,
            "beforeLink" => null,
        ];
        if (isset($next)) {
            $variables = [
                "cursor" => $next,
                "last" => null,
                "next" => 3,
                "beforeLink" => null,
            ];
        }
        if (isset($previous)) {
            $variables = [
                "cursor" => null,
                "last" => 3,
                "next" => null,
                "beforeLink" => $previous,
            ];
        }

        $response = $user->api()->graph($query, $variables);

//        $response = Http::withHeaders([
//            'X-Shopify-Access-Token' => env('Shopify_Token')
//        ])->post('https://demo-hitesh-pathar.myshopify.com/admin/api/2022-04/graphql.json',[
//            'query'=>$query,'variables'=>$variables
//        ])->json();

        $products = $response['body']['data']['products'];

        return response($products);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::where('name', 'demo-aarnil-meghjani.myshopify.com')->first();
        $mutation =
            'mutation MyMutation($input: ProductDeleteInput!)
            {
                productDelete(input: $input)
                {
                    deletedProductId
                }

            }';
        $variables = [
            "input" => [
                'id' => 'gid://shopify/Product/' . $id,
            ]
        ];
        $response = $user->api()->graph($mutation, $variables);
        return response('Product Deleted Successfully', 200);
    }

    public function update(Request $request)
    {

        $user = User::where('name', 'demo-aarnil-meghjani.myshopify.com')->first();

        $mutation = 'mutation UpdateProduct($id:ID!,$title:String,$description:String) {
            productUpdate(input:
            {
                title: $title,
                descriptionHtml:$description ,
                id: $id
             })
             {
                product
                {
                    id
                }
             }
        }';

        $variables = [
            'id' => 'gid://shopify/Product/' . $request->id,
            'title' => $request->title,
            'description' => $request->description,
        ];

        $user->api()->graph($mutation, $variables);
        return response('product updated', 200);
    }

    public function filter(Request $request)
    {

        $text = $request->text;
        $status = $request->status;

        $status1 = null;
        $status2 = null;

        $user = User::where('name', 'demo-aarnil-meghjani.myshopify.com')->first();

        $query = 'query searchProduct($search:String!){
            products(first:5,query:$search)
            {
                edges{
                    node{
                        legacyResourceId
                            description
                            title
                            status
                            tags
                            vendor
                    }
                }
            }
        }';
        if (isset($text)) {
            $variables = [
                "search" => 'description:{_like:%' . $text . '%}'
            ];
        }
        if (isset($status)) {
            if (sizeof($status) === 1) {
                $status1 = $status[0];
            } else {
                $status1 = $status[0];
                $status2 = $status[1];
            }
            $variables = [
                'search' => 'status:' . $status1 . ' OR status:' . $status2
            ];
        }
        $response = $user->api()->graph($query, $variables);

        $products = $response['body']['data']['products'];

        return response($products);
    }

//    public function create(Request $request)
//    {
//
//        $request->validate([
//            'title' => 'required',
//            'description' => 'required',
//            'vendor' => 'required',
//            'price' => 'numeric|required',
//        ]);
//
//        $user = User::where('name', 'demo-aarnil-meghjani.myshopify.com')->first();
//        $mutation = 'mutation productCreate($input: ProductInput!) {
//            productCreate(input:$input)
//             {
//                product
//                {
//                    id
//                }
//             }
//        }';
//
//        $variables = [
//            'input' => [
//                'title' => $request->title,
//                'description' => $request->description,
//                'vendor' => $request->vendor,
//                'variants'=>[
//                    'price'=> $request->price,
//                ]
//            ]
//        ];
//        $user->api()->graph($mutation, $variables);
//
//        return response('Product created', 200);
//
//    }




    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'vendor' => 'required',
            'price' => 'numeric|required',
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        } else {

            $user = User::where("name", "=", "demo-aarnil-meghjani.myshopify.com")->first();//fixed its comes from middleware
        $mutation = 'mutation productCreate($input: ProductInput!) {
            productCreate(input:$input)
             {
                product
                {
                    id
                }
             }
        }';



        }
        $variables = [
            'input' => [
                'title' => $request->title,
                'descriptionHtml' => $request->description,
                'vendor' => $request->vendor,
                'variants'=>[
                    'price'=> $request->price,
                ]
            ]
        ];
        $res = $user->api()->graph($mutation, $variables);

        return Response('success', 200);
    }




}
