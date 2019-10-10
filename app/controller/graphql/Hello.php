<?php
//
//namespace app\controller\graphql;
//
//use api\BaseApi;
//use app\exception\SuccessOk;
//use GraphQL\GraphQL;
//use GraphQL\Type\Definition\ObjectType;
//use GraphQL\Type\Definition\Type;
//use GraphQL\Type\Schema;
//
//class Hello extends BaseApi
//{
//    public function query()
//    {
//        $query = (new \api\GraphQL)->getQuery();
//
//        $queryType = new ObjectType([
//            'name' => 'Query',
//            'fields' => [
//                'echo' => [
//                    'type' => Type::string(),
//                    'args' => [
//                        'message' => Type::nonNull(Type::string()),
//                    ],
//                    'resolve' => function ($root, $args) {
//                        return $root['prefix'] . $args['message'];
//                    }
//                ],
//            ],
//        ]);
//
//        $schema = new Schema([
//            'query' => $queryType
//        ]);
//
//        try {
//            $rootValue = ['prefix' => 'You said: '];
//            $result = GraphQL::executeQuery($schema, $query, $rootValue, null, null);
//            $output = $result->toArray();
//        } catch (\Exception $e) {
//            $output = [
//                'errors' => [
//                    [
//                        'message' => $e->getMessage()
//                    ]
//                ]
//            ];
//        }
//
//        throw new SuccessOk($output['data'], 'nmsl');
//    }
//}
//
