
# Technical Task
This is a simple API for  Bond and Order project


## API Reference

#### GET Payouts

```http
  GET /api/v1/bond/{id}/payouts
```

| Url Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `id` | `integer` | **Required Integer** |

#### Create  Order Bond

```http
  POST  /api/v1/bond/{id}/order
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `order_date`      | `string` | **Required date** |
| `bonds_quantity`      | `integer` | **Required integer** |


```http
  POST  /api/v1/bond/order/{orderId}
```

| Url Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `$orderId`      | `integer` | **Required Integer** |


## Authors

- [@gsmat](https://www.github.com/gsmat)

