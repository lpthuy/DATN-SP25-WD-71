import { useEffect, useState } from 'react';
import { View, Text, FlatList, Button } from 'react-native';
import axios from 'axios';

export default function OrdersScreen() {
  const [orders, setOrders] = useState([]);

  const fetchOrders = async () => {
    const token = localStorage.getItem('shipper_token');
    const res = await axios.get('http://127.0.0.1:8000/api/shipper/orders', {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    setOrders(res.data.orders);
  };

  useEffect(() => {
    fetchOrders();
  }, []);

  const handleUpdateStatus = async (orderId: number) => {
    const token = localStorage.getItem('shipper_token');
    await axios.post(`http://127.0.0.1:8000/api/shipper/orders/${orderId}/update`, {
      status: 'completed'
    }, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    });
    fetchOrders(); // Refresh again
  };

  return (
    <FlatList
      data={orders}
      keyExtractor={item => item.id.toString()}
      renderItem={({ item }) => (
        <View style={{ padding: 10, borderBottomWidth: 1 }}>
          <Text>Đơn hàng: {item.order_code}</Text>
          <Text>Trạng thái: {item.status}</Text>
          <Button title="Xác nhận giao hàng" onPress={() => handleUpdateStatus(item.id)} />
        </View>
      )}
    />
  );
}
